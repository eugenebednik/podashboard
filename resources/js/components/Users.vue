<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Server PO Performance Report</div>

                    <div class="card-body">
                        <div v-if="loading">Loading...</div>
                        <div v-else>
                            <table class="table" :key="componentKey">
                                <thead>
                                <tr>
                                    <th class="text-muted" scope="col">Name</th>
                                    <th class="text-muted" scope="col">Number of Requests Fulfilled</th>
                                    <th class="text-muted" scope="col">Average Time Per Session</th>
                                    <th class="text-muted" scope="col">Total Time Spent Serving</th>
                                    <th class="text-muted" scope="col">Is admin?</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="user in users">
                                    <td :class="adminUsers.includes(user.id) ? 'text-primary' : 'text-muted'">{{ user.name }}</td>
                                    <td>{{ user.count }}</td>
                                    <td>{{ user.average_time_per_session }}</td>
                                    <td>{{ user.total_time_spent_serving }}</td>
                                    <td>
                                        <div v-if="user.id !== selfId">
                                            <button v-if="adminUsers.includes(user.id)" v-on:click="updateUser(user.id)" class="btn btn-success">Yes</button>
                                            <button v-else v-on:click="updateUser(user.id)" class="btn btn-danger">No</button>
                                        </div>
                                        <div v-else>
                                            It's you!
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="float-right">
                            <a class="btn btn-info" :href="dashboardUrl">&larr; Back to Dashboard</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Users",

        props: ['users', 'serverId', 'token', 'dashboardUrl', 'selfId'],

        data () {
            return {
                loading: true,
                componentKey: 0,
                rerenderComponent: true,
                adminUsers: [],
            }
        },

        mounted () {
            this.reload();
        },

        methods: {
            updateUser(userId) {
                axios
                    .put('/api/admin/users/' + this.serverId, {
                        user_id: userId,
                    }, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(response => {
                        if (response.status === 201 || response.status === 204) {
                            toast.fire('Success', 'User update successfully.', 'success');
                            this.reload();
                        }
                    })
                    .catch(err => {
                        toast.fire('Error', 'Something went wrong.', 'error');
                        console.log(err)
                    });
            },

            reload() {
                this.adminUsers = [];
                axios
                    .get('/api/admin/users/' + this.serverId, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(response => {
                        if (response.status === 200) {
                            response.data.administrators.forEach(admin => {
                                this.adminUsers.push(admin.id);
                            });
                        }
                    })
                    .catch(err => toast.fire('Error', 'An error has occurred loading data.', 'error'))
                    .finally(() => this.loading = false);

                this.loading = true;
                this.componentKey += 1;
            }
        }
    }
</script>

<style scoped></style>
