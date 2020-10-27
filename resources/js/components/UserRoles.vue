<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">Server PO Performance Report</div>
                    <div class="card-body">
                        <div v-if="loading">Loading...</div>
                        <div v-else>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Discord ID</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Allow/Deny Login</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="role in roles" :key="componentKey">
                                    <td :class="allowedRoles.includes(role.id) ? 'text-primary text-bold' : 'text-muted'">{{ role.id }}</td>
                                    <td :class="allowedRoles.includes(role.id) ? 'text-primary text-bold' : 'text-muted'">{{ role.name }}</td>
                                    <td>
                                        <button v-if="allowedRoles.includes(role.id)" v-on:click="updateRole(role.id, role.name)" class="btn btn-danger">Deny</button>
                                        <button v-else v-on:click="updateRole(role.id, role.name)" class="btn btn-success">Allow</button>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="card-footer">

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "UserRoles",
        props: ['roles', 'serverId', 'token'],

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
                    .put('/api/admin/roles/' + roleId + '/?server_id=' + this.serverId, {
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
                    .catch(err => console.log(err))
            },

            reload () {
                this.allowedRoles = [];
                axios
                    .get('/api/admin/server/' + this.serverId + '/?server_id=' + this.serverId, {
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
