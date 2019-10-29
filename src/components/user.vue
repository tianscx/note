<template>
    <div>
        <mt-header title="">
            <router-link to="/noteList" slot="left">
                <mt-button icon="back"></mt-button>
            </router-link>
        </mt-header>
        <span class="switch clickItem" @click="switchType">{{type=='login'?'注册':'登录'}}</span>
        <div class="main">
            <div class="loginBox" v-show="type=='login'">
                <div class="line">
                    <span>用户名：</span>
                    <input type="text" v-model="username" autocomplete="off">
                </div>
                <div class="line">
                    <span>密码：</span>
                    <input  v-model="password" type="text" autocomplete="off"  onfocus="this.type='password'">
                </div>
                <div style="margin: 8px auto;text-align: center">
                    <mt-button  size="large" type="primary" @click="login" v-if="username&&password">登录</mt-button>
                    <mt-button  size="large" disabled v-else type="primary">登录</mt-button>
                </div>
                <div style="text-align: center;margin-top: 20px">
                    <span class="clickItem" style="padding: 5px 12px;color: #515151;font-size: 14px" @click="switchType('findPassword')">忘记密码</span>
                </div>
            </div>
            <div class="loginBox" v-show="type=='sign'">
                <div class="line">
                    <span>用户名：</span>
                    <input type="text" v-model="username1" autocomplete="off">
                </div>
                <div class="line">
                    <span>密码：</span>
                    <input  v-model="password1" type="text" autocomplete="off" onfocus="this.type='password'">
                </div>
                <div class="line">
                    <span>邮箱：</span>
                    <input  v-model="email1" autocomplete="off">
                </div>
                <div style="margin: 8px auto;text-align: center">
                    <mt-button  size="large" type="primary" @click="signup" v-if="username1&&password1&&email1">注册</mt-button>
                    <mt-button  size="large" disabled v-else type="primary">注册</mt-button>
                </div>
            </div>

            <div class="loginBox" v-show="type=='findPassword'">
                <h4 style="text-align: center">找回密码</h4>
                <div class="line">
                    <span>邮箱：</span>
                    <input  v-model="find_email" autocomplete="off">
                </div>
                <div style="margin: 8px auto;text-align: center">
                    <mt-button  size="large" type="primary" @click="getUser" v-if="find_email">提交</mt-button>
                    <mt-button  size="large" disabled v-else type="primary">提交</mt-button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default{
        name:'user',
        computed:{
            deviceId(){
                return this.$store.state.device_id;
            }
        },
        data(){
            return{
                type:'login',
                username:'',
                password:'',
                username1:'',
                password1:'',
                email1:'',
                find_email:''
            }
        },
        methods:{
            switchType(n){
                if(n == 'findPassword'){
                    this.type = 'findPassword';
                    return
                }
                this.type = this.type=='login'?'sign':'login'
            },
            signup(){
                this.$store.commit('setLoading',true);
                console.log('开始')
                if(this.password1 && this.username1 && this.email1){
                    this.$.ajax({
                        method:"POST",
                        url:'add_user.php',
                        data:this.qs({
                            name:this.username1,
                            password:this.password1,
                            email:this.email1
                        })
                    }).then((res)=>{
                        if(res.code == 0){
                            this.sendEmail(this.username1,this.email1)
                        }else if(res.code == -2){
                            this.$store.commit('setLoading',false);
                            this.$toast({
                                message: '用户名已存在！',
                                position: 'top',
                                duration: 3000
                            });
                        }else if(res.code == -3){
                            this.$store.commit('setLoading',false);
                            this.$toast({
                                message: '此邮箱以被注册！',
                                position: 'top',
                                duration: 3000
                            });
                        }else if(res.code == -1){
                            this.$store.commit('setLoading',false);
                            this.$toast({
                                message: '注册失败，数据不完整！',
                                position: 'top',
                                duration: 3000
                            });
                        }else if(res.code == 1){
                            this.$store.commit('setLoading',false);
                            this.$toast({
                                message: '注册失败，请稍后重试！',
                                position: 'top',
                                duration: 3000
                            });
                        }
                    })
                }
            },
            sendEmail(name,email){
//                var baseUrl = 'http://107.175.214.24/note/activate.html'
                var baseUrl = 'http://106.13.66.238/note/activate.html'
                this.$axios({
                    method: 'post',
//                    url: 'http://107.175.214.24/mailer/mail.php',
                    url: 'http://106.13.66.238/mailer/mail.php',
                    data: this.qs({
                        mailto:email,
                        topic:'笔记应用账号激活',
                        content:"<span>请点击下面链接激活账号，如果不能跳转，请复制网址到浏览器打开。</span><br>" +
                        "<a href=" + baseUrl + "?user=" + name  + ">" + baseUrl + "?user=" + name + "<a/>"
                    })
                }).then((res=>{
                    console.log(res);
                    this.$store.commit('setLoading',false);
                    if(res.data.return == 0){
                        this.$toast({
                            message: '注册成功，激活邮件已发送，请查收！',
                            duration: 3000
                        });
                        this.type = 'login';
                        this.passive1 = this.username1 = this.email1 = ''
                    }else {
                        this.$toast({
                            message: '注册成功，但是激活邮件发送失败！',
                            position: 'top',
                            duration: 3000
                        });
                    }
                }));
            },
            login(){
                this.$store.commit('setLoading',true);
                if(this.password && this.username){
                    this.$.ajax({
                        method:"POST",
                        url:'login.php',
                        data:this.qs({
                            name:this.username,
                            password:this.password,
                        })
                    }).then((res)=>{
                        this.$store.commit('setLoading',false);
                        if(res.code == 0){
                            console.log(res.data)
                            this.$toast({
                                message: '登录成功',
                                duration: 3000,
                                position:'top'
                            });
                            if(localStorage.getItem('oldUser')){
                                var oldUser = JSON.parse(localStorage.getItem('oldUser'));
                                if(oldUser.name != this.username){
                                    this.clearLocal();
                                }
                            }
                            this.$store.commit('setUserSession',res.data);
                            this.$router.replace('/noteList');
                            location.reload();
                        }else if(res.code == -4){
                            this.$toast({
                                message: '账号未激活！',
                                duration: 3000,
                                position:'top'
                            });
                        }else if(res.code == -3){
                            this.$toast({
                                message: '用户名不存在！',
                                duration: 3000,
                                position:'top'
                            });
                        }else if(res.code == -2){
                            this.$toast({
                                message: '密码错误！',
                                duration: 3000,
                                position:'top'
                            });
                        }else {
                            this.$toast({
                                message: '登录失败',
                                duration: 3000,
                                position:'top'
                            });
                        }
                    })
                }
            },
            clearLocal(){
                this.$store.commit('setNoteArr',[]);
                this.$store.commit('setLabelArr',[{
                    value:'0',
                    label:'未标签',
                    color:'#333',
                    status:1,
                    updateTime:(new Date()).valueOf(),
                    device_id:this.deviceId
                }])
            },
            findPassword(id, email){
//                var baseUrl = 'http://107.175.214.24/note/reset.html'
                var baseUrl = 'http://106.13.66.238/note/reset.html'
                this.$axios({
                    method: 'post',
                    url: 'http://106.13.66.238/mailer/mail.php',
                    data: this.qs({
                        mailto:email,
                        topic:'重置密码',
                        content:"<span>请点击下面链接重置账号密码，如果不能跳转，请复制网址到浏览器打开。</span><br>" +
                        "<a href=" + baseUrl + "?id=" + id  + ">" + baseUrl + "?id=" + id + "<a/>"
                    })
                }).then((res=>{
                    this.$store.commit('setLoading',false);
                    console.log(res);
                    this.$store.commit('setLoading',false);
                    if(res.data.return == 0){
                        this.$toast({
                            message: '重置链接以发送至邮箱，请查收！',
                            duration: 3000
                        });
                        this.type = 'login';
                        this.find_email = ''
                    }else {
                        this.$toast({
                            message: '邮件发送失败！',
                            position: 'top',
                            duration: 3000
                        });
                    }
                }));
            },
            getUser(){
                this.$store.commit('setLoading',true);
                this.$.ajax({
                    method: 'post',
                    url: 'get_user.php',
                    data: this.qs({
                        email:this.find_email
                    })
                }).then((res)=>{
                    console.log(res);
                    if(res.code == 0){
                        this.findPassword(res.data.id,res.data.email)
                    }else if(res.code == -1){
                        this.$store.commit('setLoading',false);
                        this.$toast({
                            message: '用户不存在！',
                            position: 'top',
                            duration: 3000
                        });
                    }else {
                        this.$store.commit('setLoading',false);
                        this.$toast({
                            message: '服务器错误，请稍后重试！',
                            position: 'top',
                            duration: 3000
                        });
                    }
                })
            }
        },
        mounted(){
            if(localStorage.getItem("oldUser")){
                var oldUser = JSON.parse(localStorage.getItem("oldUser"));
                this.username = oldUser.name;
            }
        }
    }
</script>

<style scoped>
    .main{
        width: 80%;
        /*padding:8px 40px;*/
        margin:44px auto;
        position: relative;
    }
    .line{
        width: 100%;
        height: 50px;
    }
    .line>span{
        display: inline-block;
        width: 80px;
        text-align: right;
    }
    input{
        border: none;
        outline: none;
        border-bottom: 1px solid #999;
        padding:5px;
        width: calc(100% - 120px);
        font-size: 16px;
        /*vertical-align: middle;*/
        background-color: rgba(255,255,255,0);
    }
    .switch{
        padding:5px 12px;
        position: absolute;
        right: 12px;
        top:8px;
        color: rgb(13, 135, 148);
    }
</style>