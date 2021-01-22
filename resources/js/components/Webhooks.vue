<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-header">
                        Webhook Url Management
                    </div>
                    <div class="card-body">
                        <form @submit.prevent="submit">
                            <div class="alert alert-danger mt-3">
                                DANGER ZONE: Please DO NOT modify this unless you KNOW what you are doing, for this could break your dashboard!
                            </div>
                            <div v-if="success" class="alert alert-success alert-dismissible mt-3">
                                Webhook URL updated successfully.
                            </div>
                            <div class="form-group">
                                <label for="webhook_url">Webhook Url</label>
                                <input type="text" class="form-control" name="webhook_url" id="webhook_url" placeholder="Enter copy-pasted webhook URL from your discord server..." v-model="fields.webhook_url" />
                                <div v-if="errors && errors.webhook_url" class="text-danger">{{ errors.webhook_url[0] }}</div>
                            </div>
                            <button type="submit" class="btn btn-success">Submit</button>
                            <div class="float-right">
                                <a class="btn btn-info" :href="dashboardUrl">&larr; Back to Dashboard</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "Webhooks",
    props: ['serverId', 'token', 'dashboardUrl'],
    data() {
        return {
            fields: {},
            errors: {},
            loaded: true,
            success: false,
        }
    },

    methods: {
        submit() {
            if (this.loaded) {
                this.loaded = false;
                this.success = false;
                this.errors = {};
                axios.put('/api/server/' + this.serverId, this.fields, {
                    headers: {
                        'Authorization': 'Bearer ' + this.token,
                        'Accept': 'application/json',
                    }
                }).then(response => {
                    this.fields = {};
                    this.loaded = true;
                    this.success = true;
                }).catch(err => {
                    this.loaded = true;
                    if (err.response.status === 422) {
                        this.errors = err.response.data.errors || {}
                    }
                });
            }
        }
    }
}
</script>

<style scoped>

</style>
