<template>
    <div class="container">
        <div class="row justify-content-center" :key="componentKey">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">My Stats</div>
                    <div class="card-body">
                        <h3>My Completed Requests: <span class="badge badge-success">{{ countRequests }}</span></h3>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Fulfilled Requests Pending Completion</div>
                    <div class="card-body" v-if="loading">Loading...</div>

                    <div class="card-body" v-else>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Requested By</th>
                                <th scope="col">Request Type</th>
                                <th scope="col">Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="request in fulfilled">
                                <th scope="row">{{ request.id }}</th>
                                <td>{{ request.is_alt_request ? request.alt_name : request.user_name }}</td>
                                <td>{{ request.user_name }}</td>
                                <td>{{ request.request_type.name }}</td>
                                <td><span class="badge badge-warning">In Progress</span></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
                <div class="card">
                    <div class="card-header">Unfulfilled Requests</div>
                    <div class="card-body" v-if="loading">Loading...</div>

                    <div class="card-body" v-else>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Name</th>
                                <th scope="col">Requested By</th>
                                <th scope="col">Request Type</th>
                                <th scope="col">Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="request in outstanding">
                                <th scope="row">{{ request.id }}</th>
                                <td>
                                    <div id="divClipboard" style="display: inline-block">
                                        {{ request.is_alt_request ? request.alt_name : request.user_name }}
                                    </div>
                                    <button type="button" class="btn btn-info btn-clipboard" v-on:click="copyClipboard()">Copy</button>
                                </td>
                                <td>{{ request.user_name}}</td>
                                <td>{{ request.request_type.name }}</td>
                                <td><button v-on:click="fulfillRequest(request.id)" class="btn btn-primary" role="button">Fulfill</button></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Dashboard",
    props: ['serverId', 'userId', 'token'],
    data () {
        return {
            loading: true,
            componentKey: 0,
            countRequests: 0,
            outstanding: [],
            fulfilled: [],
            timer: '',
        }
    },

    created() {
        this.reload();
        this.timer = setInterval(this.reload, 10000);
    },

    methods: {
        copyClipboard() {
            let elm = document.getElementById("divClipboard");

            if (document.body.createTextRange) {
                // for Internet Explorer

                let range = document.body.createTextRange();
                range.moveToElementText(elm);
                range.select();
                document.execCommand("Copy");
                toast.fire('Copy', 'Requester name copied to clipboard.', 'success');
            }
            else if (window.getSelection) {
                // other browsers

                let selection = window.getSelection();
                let range = document.createRange();
                range.selectNodeContents(elm);
                selection.removeAllRanges();
                selection.addRange(range);
                document.execCommand("Copy");
                toast.fire('Copy', 'Requester name copied to clipboard.', 'success');
            }
        },

        fulfillRequest(id) {
            axios
                .put('/api/requests/fulfill/' + id, {
                    user_id: this.userId,
                    server_id: this.serverId,
                },{
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        toast.fire('Success', 'Roles updated successfully.', 'success');
                        this.reload();
                    }
                })
                .catch(err => {
                    toast.fire('Error', 'Something went wrong!', 'error');
                    console.log(err)
                });
        },

        reload() {
            this.allowedRoles = [];
            axios
                .get('/api/requests/?server_id=' + this.serverId, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        console.log(response.data);
                        this.outstanding = response.data.outstanding;
                        this.fulfilled = response.data.fulfilled;
                    }
                })
                .catch(err => toast.fire('Error', 'An error has occurred loading data.', 'error'))
                .finally(() => this.loading = false);

            axios
                .get('/api/requests/count/' + this.userId, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        this.countRequests = response.data.count;
                    }
                })
                .catch(err => toast.fire('Error', 'An error has occurred loading data.', 'error'))
                .finally(() => this.loading = false);

            this.loading = true;
            this.componentKey += 1;
        }
    },

    beforeDestroy() {
        clearInterval(this.timer);
    }
}
</script>

<style scoped>
</style>
