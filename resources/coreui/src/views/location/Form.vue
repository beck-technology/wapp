<template>
    <div class="animated fadeIn">
        <div class="row">
            <div class="col-sm-12">
                <form enctype="multipart/form-data" autocomplete='off' @submit.prevent="submitForm">
                    <div class="card border-0">
                        <div class="form-group form-actions">
                            <router-link :to="{ path: `/company/${this.location.company_id}`}">
                                <button type="button" class="btn btn-sm btn-warning pull-right">Cancel</button>
                            </router-link>
                            <button type="button" @click.prevent="submit" style="margin-right:10px" class="btn btn-sm btn-primary pull-right">Submit</button>
                            <!-- <button type="button" @click.prevent="submit" class="btn btn-block btn-success">Create Account</button> -->
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Address">
                                        <b-form-input type="text" :class="errors.has('address') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="address" v-model="location.address" placeholder="Enter Address"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Post Code">
                                        <b-form-input type="text" :class="errors.has('post_code') ? 'form-control is-invalid':'form-control'" v-validate="'required'"
                                            name="post_code" v-model="location.post_code" placeholder="Enter Post Code"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-sm-6">
                                    <label for="">Select Contact</label>
                                    <b-form-select v-model="selectContact" class="mb-3">
                                        <option :value="null">Please select an option</option>
                                        <option v-for="user in users" v-bind:key="user.id" v-bind:value="user.id">{{user.first_name+" "+user.last_name}}</option>
                                    </b-form-select>
                                    <!-- <option value="1" selected>Ivy Lui</option> -->
                                    <div class="invalid-feedback">Please Select Contact</div>
                                </div>
                                <div class="form-group col-sm-6">
                                    <b-form-fieldset label="Phone Number">
                                        <b-form-input type="text" v-model="location.phone_number" placeholder="Phone Number"></b-form-input>
                                    </b-form-fieldset>
                                </div>
                            </div>
                            {{location}} {{edit}}
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        name: "forms",
        components: {},
        data() {
            return {
                users: [],
                selectContact:null,
                location: {
                    // company_id: 1
                },
                edit: false
            };
        },
        created() {
            console.log(this.$store.state.user);
            if (!this.$store.state.user) this.$router.push("/login");
            this.location.account_id = this.$store.state.user.account_id;
            this.location.company_id = this.$route.params.id;
            this.fetchUsers();
            // this.location.user_id = this.$store.state.user.id;
            // console.log('look for user id',this.location.user_id);
            console.log("look for company id", this.$route.params.id);
            if (this.$route.path.indexOf("create") > -1) {
                console.log("show result", this.$route.path.indexOf("create"));
                this.edit = false;
            } else {
                this.edit = true;
                this.fetchById(this.$route.params.id);
            }

            if (this.$route.params.id) {
                // this.edit = true;
                // //for edit by id
                // this.fetchById(this.$route.params.id);
            }
        },
        methods: {
            submit() {
                this.$validator.validate().then(result => {
                    if (result) this.submitForm();
                });
            },
            submitForm() {
                if (this.edit) {
                    this.location.updated_by = this.$store.state.user.id;
                    this.$http
                        .put(`/api/location`, this.location)
                        .then(response => {
                            // this.$router.push("/locations/");
                            this.$router.push({ path: `/company/${this.location.company_id}` });
                        })
                        .catch(error => {
                            this.$setErrorsFromResponse(err.response.data);
                            this.$emit("error", err.response.data);
                        });
                } else {
                    this.location.created_by = this.$store.state.user.id;
                    this.$http
                        .post(`/api/location`, this.location)
                        .then(response => {
                            // this.$router.push("/locations/");
                            this.$router.push({ path: `/company/${this.location.company_id}` });
                        })
                        .catch(error => {
                            console.log(error);
                            this.$setErrorsFromResponse(err.response.data);
                            this.$emit("error", err.response.data);
                        });
                }
            },
            fetchById(id) {
                this.$http.get(`/api/location/${id}`).then(response => {
                    this.location = response.data.data;
                    console.log("location", this.location);
                });
            },
            fetchUsers() {
                this.$http.get(`/api/users`).then(response => {
                    this.users = response.data.data;
                    console.log("location", this.users);
                });
            }
        }
    };
</script>