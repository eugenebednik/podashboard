<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">User Management</div>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Create New</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Alliance</th>
                                <th>Email</th>
                                <th>Is Admin?</th>
                                <th>Is Active?</th>
                                <th>Requests Served</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="user, index in users">
                                <td>{{ user.name }}</td>
                                <td>{{ user.alliance.name }}</td>
                                <td>{{ user.email }}</td>
                                <td>{{ user.is_admin ? 'Yes' : 'No' }}</td>
                                <td>{{ user.active ? 'Yes' : 'No' }}</td>
                                <td>{{ requestsServed(user.id) }}</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-primary" @click="editModal(user)">
                                        Edit
                                    </a>
                                    <a href="#" class="btn btn-xs btn-danger" @click="deleteEntry(user.id, index)">
                                        Delete
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="addNew" tabindex="-1" role="dialog" aria-labelledby="addNewLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" v-show="!editmode" id="addNewLabel">Add New</h5>
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update User's Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateUser() : createUser()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">Name</label>
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="Name"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name" />
                            </div>

                            <div class="form-group">
                                <label class="control-label">Alliance</label>
                                <select v-model="form.alliance_id" class="form-control" :class="{ 'is-invalid': form.errors.has('alliance')  }">
                                    <option v-for="alliance in playerAlliances" :value="alliance.id">{{alliance.name}}</option>
                                </select>
                                <has-error :form="form" field="email" />
                            </div>

                            <div class="form-group">
                                <label class="control-label">Email</label>
                                <input v-model="form.email" type="email" name="email"
                                       placeholder="Email Address"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('email') }">
                                <has-error :form="form" field="alliance_id" />
                            </div>

                            <div class="form-group" v-if="!editmode || !isNotSelf(form.id)">
                                <label class="control-label">Password</label>
                                <input v-model="form.password" type="password" name="password" id="password"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password') }">
                                <has-error :form="form" field="password" />
                            </div>

                            <div class="form-group" v-if="!editmode || !isNotSelf(form.id)">
                                <label class="control-label">Confirm Password</label>
                                <input v-model="form.password_confirmation" type="password" name="password_confirmation" id="password_confirmation"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('password_confirmation') }">
                                <has-error :form="form" field="password" />
                            </div>

                            <div class="form-group" v-if="isNotSelf(form.id)">
                                <label class="control-label">Is Admin?</label>
                                <select v-model="form.is_admin" id="is_admin" class="form-control" :class="{ 'is-invalid': form.errors.has('is_admin')  }">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <has-error :form="form" field="is_admin" />
                            </div>

                            <div class="form-group" v-if="isNotSelf(form.id)">
                                <label class="control-label">Is Active?</label>
                                <select v-model="form.active" id="active" class="form-control" :class="{ 'is-invalid': form.errors.has('active')  }">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                                <has-error :form="form" field="active" />
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                            <button v-show="editmode" type="submit" class="btn btn-success">Update</button>
                            <button v-show="!editmode" type="submit" class="btn btn-primary">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['token', 'alliances', 'user', 'served'],
        data: function () {
            return {
                editmode: false,
                users: [],
                form: new Form({
                    id:'',
                    name : '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    is_admin: '',
                    alliance_id: '',
                    active: '',
                }),
                options: {
                    headers: {
                        'Accept': 'application/json',
                        'Authorization': `Bearer ${this.token}`,
                    }
                },
            }
        },
        computed: {
            playerAlliances: function() {
                return JSON.parse(this.alliances);
            },
            playersServed: function () {
                return JSON.parse(this.served);
            }
        },
        created() {
            this.loadUsers();
            Fire.$on('AfterCreate',() => {
                this.loadUsers();
            });
        },
        methods: {
            requestsServed(id) {
                let served = this.playersServed;
                return served[id];
            },
            isNotSelf(userId) {
                return this.user != userId;
            },
            updateUser() {
                this.$Progress.start();
                this.form.put('/api/users/'+this.form.id, this.options)
                    .then(() => {
                        $('#addNew').modal('hide');
                        swal.fire(
                            'Updated!',
                            'Information has been updated.',
                            'success'
                        );
                        this.$Progress.finish();
                        Fire.$emit('AfterCreate');
                    })
                    .catch(() => {
                        this.$Progress.fail();
                    });
            },
            createUser() {
                this.$Progress.start();
                this.form.post('/api/users', this.options)
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide');
                        toast.fire('Success', 'User created successfully', 'success');
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    })
            },
            editModal(user) {
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(user);
            },
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            getResults(page = 1) {
                axios.get('/api/users?page=' + page, this.options)
                    .then(response => {
                        this.users = response.data;
                    });
            },
            deleteEntry(id, index) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete this user!'
                }).then((result) => {
                    // Send request to the server
                    if (result.value) {
                        this.form.delete('/api/users/'+id, this.options).then(()=>{
                            swal.fire(
                                'Deleted!',
                                'The user has been deleted.',
                                'success'
                            );
                            Fire.$emit('AfterCreate');
                        }).catch(()=> {
                            swal.fire("Failed!", "There was something wrong.", "warning");
                        });
                    }
                });
            },
            loadUsers() {
                axios.get("/api/users", this.options).then(({ data }) => (this.users = data));
            },
        }
    }
</script>
