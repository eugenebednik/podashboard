<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-muted">Discord Role Management</h5>
                    </div>

                    <div class="card-body">
                        <div>
                            <p>This page allows you to specify which Discord roles on your server are allowed to login to the Dashboard.
                                Users having <span class="text-success">allowed</span> roles can login to the system, while users that have <span class="text-danger">denied</span> roles cannot login.
                            </p>
                        </div>
                        <div v-if="loading">Loading...</div>
                        <div v-else>
                            <table class="table" :key="componentKey">
                                <thead>
                                <tr>
                                    <th class="text-muted" scope="col">Name</th>
                                    <th class="text-muted" scope="col">Allow/Deny Login</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="role in roles" v-if="role.name.toLowerCase() !== 'protocol officer dashboard'">
                                    <td :class="allowedRoles.includes(role.id) ? 'text-primary' : 'text-muted'">{{ role.name }}</td>
                                    <td>
                                        <button v-if="allowedRoles.includes(role.id)" v-on:click="updateRole(role.id, role.name)" class="btn btn-success">Allowed</button>
                                        <button v-else v-on:click="updateRole(role.id, role.name)" class="btn btn-danger">Denied</button>
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
        name: "UserRoles",
        props: ['roles', 'serverId', 'token', 'dashboardUrl'],

        data () {
            return {
                loading: true,
                componentKey: 0,
                rerenderComponent: true,
                allowedRoles: [],
            }
        },

        mounted () {
            this.reload();
        },

        methods: {
            updateRole (roleId, roleName) {
                axios
                    .put('/api/admin/roles/' + this.serverId, {
                        role_id: roleId,
                        role_name: roleName,
                    },{
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(response => {
                        if (response.status === 201 || response.status === 204) {
                            toast.fire('Success', 'Roles updated successfully.', 'success');
                            this.reload();
                        }
                    })
                    .catch(err => {
                        toast.fire('Error', 'Something went wrong.', 'error');
                        console.log(err)
                    });
            },

            reload () {
                this.allowedRoles = [];
                axios
                    .get('/api/admin/server/' + this.serverId, {
                        headers: {
                            'Authorization': 'Bearer ' + this.token
                        }
                    })
                    .then(response => {
                        if (response.status === 200) {
                            response.data.allowed_roles.forEach(role => {
                                this.allowedRoles.push(role.role_id);
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

<style scoped>
</style>
