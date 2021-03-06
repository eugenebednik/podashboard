<template>
    <div class="container" :key="componentKey">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div v-if="noWebhooks" class="alert alert-danger">
                    <p>It appears that you have no <a href="https://support.discord.com/hc/en-us/articles/228383668-Intro-to-Webhooks" target="_blank">Webhook</a> URL entered your server!</p>
                    <p>It will <span class="text-danger">severely limit this Dashboard's functionality</span>. <span v-if="isUserAdmin">Please go and <a :href="webhookUrl">edit your Webhook URL now</a>.</span></p>
                </div>
                <div class="card">
                    <div class="card-header">
                        <h5 class="text-muted">My Statistics</h5>
                        <div class="float-right">
                            <button
                                :class="this.onDuty.id === this.userId ? 'btn btn-danger' : 'btn btn-success'"
                                v-on:click="signOnOffDuty"
                            >Sign {{ this.onDuty.id === this.userId ? 'Off' : 'On'}}</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="(this.onDuty.id !== this.userId) && (this.onDuty.id !== 0)" class="row">
                            <div class="col-12">
                                <div class="alert alert-warning text-center" role="alert">
                                    Warning! Officer <b>{{ this.onDuty.name }}</b> is currently an active user on PO duty in this kingdom. If you sign on you will sign them offline!
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-3 text-center">
                                <h6>Requests served: <span class="badge badge-success">{{ countRequests }}</span></h6>
                            </div>
                            <div class="col-sm-6 text-center">
                                <h6>Avg. time per session: <span class="badge badge-info">{{ avgTime }}</span></h6>
                            </div>
                            <div class="col-sm-3 text-center">
                                <h6>Total time: <span class="badge badge-info">{{ totalTime }}</span></h6>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    &nbsp;
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="text-muted">Fulfilled Requests Pending Completion</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-muted" scope="col">#</th>
                                <th class="text-muted" scope="col">Name</th>
                                <th class="text-muted" scope="col">Requested By</th>
                                <th class="text-muted" scope="col">Request Type</th>
                                <th class="text-muted" scope="col">Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="request in fulfilled">
                                <th scope="row">{{ request.id }}</th>
                                <td><span class="notranslate">{{ request.is_alt_request ? request.alt_name : request.user_name }}</span></td>
                                <td><span class="notranslate">{{ request.user_name }}</span></td>
                                <td>{{ request.request_type.name }}</td>
                                <td><span class="badge badge-warning">In Progress</span></td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                </div>

                <div>
                    &nbsp;
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="text-muted">Unfulfilled Requests</h5>
                    </div>
                    <div class="card-body">
                        <table class="table">
                            <thead>
                            <tr>
                                <th class="text-muted" scope="col">#</th>
                                <th class="text-muted" scope="col">Name</th>
                                <th class="text-muted" scope="col">Requested By</th>
                                <th class="text-muted" scope="col">Request Type</th>
                                <th class="text-muted" scope="col">Handle</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="request in outstanding">
                                <th scope="row">{{ request.id }}</th>
                                <td>
                                    <div id="divClipboard" class="notranslate" style="display: inline-block">
                                        {{ request.is_alt_request ? request.alt_name : request.user_name }}
                                    </div>
                                    <button type="button" class="btn btn-primary btn-clipboard" v-on:click="copyClipboard()">Copy</button>
                                </td>
                                <td><span class="notranslate">{{ request.user_name}}</span></td>
                                <td>{{ request.request_type.name }}</td>
                                <td><button v-on:click="fulfillRequest(request.id)" class="btn btn-info" role="button">Fulfill</button></td>
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
    props: ['serverId', 'userId', 'onDutyUser', 'token', 'webhookUrl', 'isUserAdmin'],
    data () {
        return {
            componentKey: 0,
            componentKeyTwo: 1,
            countRequests: 0,
            avgTime: 'n/a',
            totalTime: 'n/a',
            onDuty: {
                id: 0,
                name: '',
            },
            outstanding: [],
            fulfilled: [],
            timer: '',
            noWebhooks: false,
        }
    },

    mounted() {
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

        signOnOffDuty() {
            axios
                .put('/api/on-duty/' + this.serverId, {
                    user_id: this.userId,
                },{
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 201) {
                        toast.fire('Success', 'PO is online. You have signed on!', 'success');
                        this.reload();
                    } else if (response.status === 204) {
                        toast.fire('Success', 'PO is offline. You have signed off!', 'success');
                        this.reload();
                    }
                })
                .catch(err => {
                    toast.fire('Error', 'Something went wrong!', 'error');
                    console.log(err)
                });
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
                        toast.fire('Success', 'Request has been fulfilled.', 'success');
                        this.reload();
                    }
                })
                .catch(err => {
                    toast.fire('Error', 'Something went wrong!', 'error');
                    console.log(err)
                });
        },

        reload() {
            axios
                .get('/api/server/' + this.serverId, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        this.onDuty.id = response.data.on_duty ? response.data.on_duty.user.id : 0;
                        this.onDuty.name = response.data.on_duty ? response.data.on_duty.user.name : '';
                        this.noWebhooks = !response.data.webhook_id;
                    }
                })
                .catch(err => toast.fire('Error', 'An error has occurred loading data.', 'error'));
            axios
                .get('/api/requests', {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        this.outstanding = response.data.outstanding;
                        this.fulfilled = response.data.fulfilled;
                    }
                })
                .catch(err => toast.fire('Error', 'An error has occurred loading data.', 'error'));

            axios
                .get('/api/requests/count/' + this.userId, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token
                    }
                })
                .then(response => {
                    if (response.status === 200) {
                        this.countRequests = response.data.count;
                        this.avgTime = response.data.avg_time;
                        this.totalTime = response.data.total_time;
                    }
                })
                .catch(err => toast.fire('Error', 'An error has occurred loading data.', 'error'));

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
