<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card card-widget widget-user">
                    <div class="widget-user-header text-white" style="background: url('./img/user-cover.jpg');">
                        <h3 class="widget-user-username">{{ this.form.name }}</h3>
                        <h5 class="widget-user-desc">{{ this.form.type }}</h5>
                    </div>
                    <div class="widget-user-image">
                        <img class="img-circle" :src="getProfilePhoto()" alt="User Avatar">
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">3,200</h5>
                                    <span class="description-text">SALES</span>
                                </div>
                            </div>
                            <div class="col-sm-4 border-right">
                                <div class="description-block">
                                    <h5 class="description-header">13,000</h5>
                                    <span class="description-text">FOLLOWERS</span>
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <div class="description-block">
                                    <h5 class="description-header">35</h5>
                                    <span class="description-text">PRODUCTS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- tab -->

            <div class="col-md-12">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link" href="#activity" data-toggle="tab">Activity</a></li>
                            <li class="nav-item"><a class="nav-link active show" href="#settings" data-toggle="tab">Settings</a></li>
                        </ul>
                    </div><!-- /.card-header -->
                    <div class="card-body">
                        <div class="tab-content">
                            <!-- Activity Tab -->
                            <div class="tab-pane" id="activity">
                                <h3 class="text-center">Display User Activity</h3>
                            </div>
                            <!-- Setting Tab -->
                            <div class="tab-pane active show" id="settings">
                                <form class="form-horizontal">
                                    <div class="form-group">
                                        <label for="inputName" class="col-sm-2 control-label">Name</label>

                                        <div class="col-sm-12">
                                            <input type="text" v-model="form.name" class="form-control" id="inputName"
                                                placeholder="Name" :class="{ 'is-invalid': form.errors.has('name') }">
                                            <has-error :form="form" field="name"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputEmail" class="col-sm-2 control-label">Email</label>

                                        <div class="col-sm-12">
                                            <input type="email" v-model="form.email" class="form-control" id="inputEmail"
                                                placeholder="Email" :class="{ 'is-invalid': form.errors.has('email') }">
                                            <has-error :form="form" field="email"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="inputBio" class="col-sm-2 control-label">Bio</label>

                                        <div class="col-sm-12">
                                            <textarea v-model="form.bio" class="form-control" id="inputBio"
                                                placeholder="Bio" :class="{ 'is-invalid': form.errors.has('bio') }"></textarea>
                                            <has-error :form="form" field="bio"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="photo" class="col-sm-2 control-label">Profile Photo</label>
                                        <div class="col-sm-12">
                                            <input type="file" @change="updateProfile" name="photo" class="form-input">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password" class="col-sm-12 control-label">
                                            Password
                                        </label>

                                        <div class="col-sm-12">
                                            <input type="password" v-model="form.password" class="form-control" id="password"
                                                placeholder="Password" :class="{ 'is-invalid': form.errors.has('password') }">
                                            <has-error :form="form" field="password"></has-error>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-offset-2 col-sm-12">
                                            <button @click.prevent="updateInfo" type="submit" class="btn btn-success">Update</button>
                                        </div>
                                    </div>
                                </form>
                            </div><!-- /.tab-pane -->
                        </div><!-- /.tab-content -->
                    </div><!-- /.card-body -->
                </div><!-- /.nav-tabs-custom -->
            </div><!-- end tabs -->

        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                form: new Form({
                    id: '',
                    name: '',
                    email: '',
                    password: '',
                    type: '',
                    bio: '',
                    photo: '',
                }),
            }
        },
        methods: {
            async loadProfile() {
                this.$Progress.start();

                try {
                    const reponse = await axios.get('/api/profile');

                    this.form.fill(data);
                    this.$Progress.finish();
                } catch (error) {
                    //
                }
            },
            getProfilePhoto() {
                let photo = (this.form.photo.length > 0) ? '/img/profile/' + this.form.photo : '/img/profile.png';

                return photo;
            },
            async updateInfo() {
                this.$Progress.start();

                if (this.form.password == '') {
                    this.form.password = undefined;
                }

                try {
                    const response = await this.form.put('/api/profile');

                    Fire.$emit('AfterCreate');
                    this.$Progress.finish();
                } catch (error) {
                    this.$Progress.fail();
                }
            },
            updateProfile(e) {
                let file = e.target.files[0];
                let reader = new FileReader();
                let limit = 1024 * 1024 * 2;

                if (file['size'] > limit) {
                    swal({
                        type: 'error',
                        title: 'Oops...',
                        text: 'You are uploading a large file',
                    });

                    return false;
                }

                reader.onload = (file) => {
                    this.form.photo = reader.result;
                }

                reader.readAsDataURL(file);
            },
        },
        created() {
            this.loadProfile();
        },
    }
</script>

<style>
    .widget-user-header {
        background-position: center center;
        background-size: cover;
        height: 250px !important;
    }

    .widget-user .card-footer {
        padding: 0;
    }
</style>
