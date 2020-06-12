<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Alliance Management</div>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="newModal">Create New</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Tag</th>
                                <th>Requests Served</th>
                                <th style="width: 200px">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="alliance, index in alliances">
                                <td>{{ alliance.name }}</td>
                                <td>{{ requestsServed(alliance.id) }}</td>
                                <td>
                                    <a href="#" class="btn btn-xs btn-primary" @click="editModal(alliance)">
                                        Edit
                                    </a>
                                    <a href="#" class="btn btn-xs btn-danger" @click="deleteEntry(alliance.id, index)" v-show="isNotSelf(alliance.id)">
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
                        <h5 class="modal-title" v-show="editmode" id="addNewLabel">Update Alliance Info</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form @submit.prevent="editmode ? updateAlliance() : createAlliance()">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="control-label">Alliance Tag</label>
                                <input v-model="form.name" type="text" name="name"
                                       placeholder="3 letters"
                                       class="form-control" :class="{ 'is-invalid': form.errors.has('name') }">
                                <has-error :form="form" field="name" />
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
        props: ['token', 'served', 'alliance'],
        data: function () {
            return {
                editmode: false,
                alliances: [],
                form: new Form({
                    id:'',
                    name : '',
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
            playersServed: function () {
                return JSON.parse(this.served);
            }
        },
        created() {
            this.loadAlliances();
            Fire.$on('AfterCreate',() => {
                this.loadAlliances();
            });
        },
        methods: {
            isNotSelf(id) {
                return this.alliance != id;
            },
            requestsServed(id) {
                let served = this.playersServed;
                return served[id];
            },
            updateAlliance() {
                this.$Progress.start();
                this.form.put('/api/alliances/'+this.form.id, this.options)
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
            createAlliance() {
                this.$Progress.start();
                this.form.post('/api/alliances', this.options)
                    .then(()=>{
                        Fire.$emit('AfterCreate');
                        $('#addNew').modal('hide');
                        toast.fire('Success', 'Alliance created successfully', 'success');
                        this.$Progress.finish();
                    })
                    .catch(()=>{
                        this.$Progress.fail();
                    })
            },
            editModal(alliance) {
                this.editmode = true;
                this.form.reset();
                $('#addNew').modal('show');
                this.form.fill(alliance);
            },
            newModal() {
                this.editmode = false;
                this.form.reset();
                $('#addNew').modal('show');
            },
            getResults(page = 1) {
                axios.get('/api/alliances?page=' + page, this.options)
                    .then(response => {
                        this.alliances = response.data;
                    });
            },
            deleteEntry(id, index) {
                swal.fire({
                    title: 'Are you sure?',
                    text: "All users belonging to this alliance will be deleted! You won't be able to revert this!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete this alliance!'
                }).then((result) => {
                    // Send request to the server
                    if (result.value) {
                        this.form.delete('/api/alliances/'+id, this.options).then(()=>{
                            swal.fire(
                                'Deleted!',
                                'The alliance has been deleted.',
                                'success'
                            );
                            Fire.$emit('AfterCreate');
                        }).catch(()=> {
                            swal.fire("Failed!", "There was something wrong.", "warning");
                        });
                    }
                });
            },
            loadAlliances() {
                axios.get("/api/alliances", this.options).then(({ data }) => (this.alliances = data));
            },
        }
    }
</script>
