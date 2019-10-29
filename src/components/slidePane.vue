<template>
  <div class="slidePage" @touchstart="touchstart" @touchend="touchend" @touchmove="touchmove" :style="{left:slideLeft + 'px'}" :class="{ transition: isTransition }">
    <div class="slidePage-head">
      <span @click="goLogin" v-if="!user">登录</span>
      <span v-if="user">{{user.name}}</span>
        <span class="logout" v-if="user" @click="logout">退出</span>
    </div>
    <div class="slidePage-content">
      <div class="slidePage-group" @click="closeSlide">
        <div class="group-item clickItem" @click="changeFilterType('all')">
            <div class="groupItemBox">
                <img src="../assets/note.png" alt="">
                <span>全部笔记</span>
                <span class="group-item-num">{{usableNote.length}}</span>
            </div>
        </div>
        <div class="group-item clickItem" @click="changeFilterType('collect')">
            <div class="groupItemBox">
                <img src="../assets/star.png" alt="">
                <span>我的收藏</span>
                <span class="group-item-num">{{collectNote.length}}</span>
            </div>
        </div>
          <div class="group-item clickItem" @click="changeFilterType('lock',$event)" v-if="user">
              <div class="groupItemBox">
                  <img src="../assets/lock.png" alt="" style="width: 18px;margin: 2px;">
                  <span>我的加密</span>
                  <span></span>
                  <i style="float:right;padding: 3px 8px;margin-right: -10px;margin-top: -3px;" class="clickItem" @click="openResetSafe()" stop="true">
                      <font-awesome-icon :icon="['fas', 'edit']" style="color: #999;font-size: 18px" stop="true"></font-awesome-icon>
                  </i>

              </div>
          </div>
      </div>
      <div style="background: #fff;padding: 12px 18px;margin-bottom: -1px">
          <span style="color: #616161;font-size: 16px">标签</span>
          <span style="color: rgb(13, 135, 148);padding: 3px 5px;float: right;display: inline-block;margin-top: -4px;margin-right: -10px;" @click="goEdit" class="clickItem">编辑</span>
      </div>
      <div class="slidePage-group" @click="closeSlide">
        <div class="group-item clickItem" v-for="label in usableLabel" @click="changeFilterType(label.value)">
            <div class="groupItemBox">
                <span  v-if="label.value == 0" style="display: inline-block;width: 18px;height: 18px">
            <span style="font-size: 18px;position: relative">
            <font-awesome-icon :icon="['fas', 'bookmark']" style="color: #333;font-size: 18px;position: absolute;top: 0;left: 0"></font-awesome-icon>
            <font-awesome-icon :icon="['fas', 'bookmark']" style="color: #fff;font-size: 15.5px;position: absolute;top: 1px; left: 1px"></font-awesome-icon>
          </span>
          </span>
                <span v-if="label.value !=0" class="label-icon">
            <font-awesome-icon :icon="['fas', 'bookmark']" style="font-size: 18px" :style="{color:label.color}"></font-awesome-icon>
          </span>

                <span class="labelName">{{label.label}}</span>
                <span>{{label.number}}</span>
            </div>

        </div>

      </div>
        <div style="background: #fff;padding: 12px 18px; margin-top:-14px">
            <span style="color: rgb(13, 135, 148);padding: 3px 5px;" class="clickItem" @click="addLabel">新建</span>
        </div>

        <div class="slidePage-group" @click="closeSlide" style="margin-top: 14px">
            <div class="group-item clickItem" @click="feedback()">
                <div class="groupItemBox">
                    <img src="../assets/feedback.png" style="width: 18px; margin: 2px" alt="">
                    <span>反馈</span>
                    <span class="group-item-num"></span>
                </div>
            </div>
        </div>
    </div>

      <!--标签弹窗-->
      <mt-popup
          style="width: 100%"
          v-model="labelPopupVisible"
          position="bottom"
          modal="true"
          :closeOnClickModal="false">
          <div style="width: 100%;padding:12px 20px">
              <h3 style="text-align: left">新建标签</h3>
              <div style="width: calc(100% - 40px)">
                  <font-awesome-icon :icon="['fas', 'bookmark']" style="font-size: 20px;margin-left: 5px;" :style="{color:addLabelColor}"></font-awesome-icon>
                  <input class="addInput" type="text"  v-model="addLabelName">
              </div>
              <div class="popupButton">
                  <div><span class="clickItem" @click="cancelAdd">取消</span></div>
                  <div><span class="clickItem" :style="{color:addLabelName==''?'#777':'#333'}" @click="commitAdd">确定</span></div>
              </div>
          </div>
      </mt-popup>
  </div>
</template>

<script>
  export default{
    name: 'slidePane',
    computed:{
        usableNote(){
            return this.$store.getters.usableNote;
        },
        collectNote(){
            return this.$store.getters.collectNote;
        },
        lockNote(){
            return this.$store.getters.lockNote;
        },
        labelArr(){
            return this.$store.state.labelArr;
        },
        usableLabel(){
            return this.$store.getters.usableLabel;
        },
        colors(){
            return this.$store.state.colors;
        },
        user(){
            return this.$store.state.user
        },
        noteArr(){
            return this.$store.state.noteArr;
        },
        update(){
            return this.$store.state.update;
        },
        deviceId(){
            return this.$store.state.device_id;
        },
        filterType(){
            return this.$store.state.filterType;
        }
    },
    data () {
      return {
        msg: 'slidePane',
          labelPopupVisible:false,
          addLabelColor:'#333',
          addLabelName:'',
          slideLeft:0,
          touchStartX:0,
          isTransition:false,
          slideWidth:0,
          touchTimeStamp:0
      }
    },
    methods:{
      closeSlide(){
          this.$store.commit('setGlobalBg',1);
          setTimeout(()=>{
              this.$store.commit('setSlide');

          },100)
      },
      changeFilterType(n,e){
//          console.log(e)
          if(e && e.target && (e.target.localName == 'svg' || e.target.localName == 'i' || e.target.localName == 'path')){
              return
          }
          if(this.filterType == n){
              return
          }
          if(n == 'lock' && this.user){
              this.openLock();
              return;
          }
        this.$store.commit('setFilterType',n)
      },
      openLock(){
          if(this.getCookie('safe_password')){
              //cookie有加密密码，直接打开
              this.$store.commit('setFilterType','lock');
          }else {
              //判断当前用户有无加密密码，若无设置，若有则输入验证
              console.log(this.user.safe_password);
              if(this.user.safe_password){
                  this.$messageBox.prompt('请输入隐私密码').then(({ value, action }) => {
                      if(value == this.user.safe_password){
                          this.$store.commit('setFilterType','lock');
                          this.setCookie('safe_password', value, 180)
                      }else {
                          this.$messageBox({
                              title: '提示',
                              message: '隐私密码错误',
                              showCancelButton: false
                          });
                      }
                  }).catch(action=>{
                      return false;
                  });
              }else {
                  this.$messageBox.prompt('请设置隐私密码（用来访问加密笔记，并非登录密码）').then(({ value, action }) => {
                      console.log(value,action);
                      if(value){
                          this.$.ajax({
                              method:"POST",
                              url:'set_safe_password.php',
                              data:this.qs({
                                  safe_password:value,
                                  user_id:this.user.id
                              })
                          }).then((res)=>{
                              if(res.code == 0){
                                  this.$store.commit('setFilterType','lock');
                                  //更新本地缓存用户信息
                                  var userInf = JSON.stringify(this.user);
                                  userInf = JSON.parse(userInf);
                                  userInf.safe_password = value;
                                  this.$store.commit('setUserSession',userInf);
                                  //写入cookie
                                  this.setCookie('safe_password', value, 180)
                              }
                          })
                      }else {
                          this.$messageBox({
                              title: '提示',
                              message: '隐私密码不能设置为空',
                              showCancelButton: false
                          });
                      }

                  }).catch(action=>{
                      return false;
                  });
              }
          }
      },
      setLabelNum(){
        for(var a = 0; a < this.usableLabel.length; a++){
            this.usableLabel[a].number = this.$store.getters.labelNote(this.usableLabel[a].value).length;
        }
        this.$forceUpdate()
      },
        goEdit(){
          this.closeSlide();
          setTimeout(()=> {
              this.$router.push('/editLabel')
          },300)
        },
        addLabel(){
          this.labelPopupVisible = true;
            var colorIndex = this.labelArr.length%15==0?0:this.labelArr.length%15-1
            this.addLabelColor = this.colors[colorIndex];
        },
        cancelAdd(){
          this.labelPopupVisible = false;
          this.addLabelName = ''
        },
        commitAdd(){
            if(!this.addLabelName){
                this.$toast({
                    message: '标签名不能为空',
                    position: 'bottom',
                    duration: 3000,
                    className:'toast'
                });
                return
            }
            var isExist = false;
            for(var a = 0; a < this.usableLabel.length; a++){
                if(this.usableLabel[a].label == this.addLabelName){
                    isExist = true;
                    this.$toast({
                        message: '标签名已存在',
                        position: 'bottom',
                        duration: 3000,
                        className:'toast'
                    });
                    break;
                }
            }
            if(isExist){
                return
            }
            var newLabelArr =[{
                value:String(this.labelArr.length),
                label:this.addLabelName,
                color:this.addLabelColor,
                status:1,
                updateTime:(new Date()).valueOf(),
                device_id:this.deviceId
            }].concat(this.labelArr)
            this.$store.commit('setLabelArr',newLabelArr);
            this.labelPopupVisible = false;
            this.addLabelName = ''
        },
        goLogin(){
            this.closeSlide();
            setTimeout(()=> {
                this.$router.push('/user')
            },300)
        },
        logout(){
            localStorage.setItem('oldUser', JSON.stringify(this.user));
            this.$store.commit('removeUserSession')
        },
        updateNote(first){
            var noteCount = 0;
            for(let a = 0; a < this.noteArr.length; a++){
                this.$.ajax({
                    method:"POST",
                    url:'updateNote.php',
                    data:this.qs({
                        user_note_id:this.noteArr[a].user_note_id,
                        user_id:this.user.id,
                        label:this.noteArr[a].label,
                        collect:this.noteArr[a].collect,
                        time:this.noteArr[a].time,
                        updateTime:this.noteArr[a].updateTime,
                        content:this.noteArr[a].content,
                        status:this.noteArr[a].status,
                        device_id:this.noteArr[a].device_id,
                        islock:this.noteArr[a].islock
                    })
                }).then((res)=>{
                    if(res.code == 0){
                        noteCount++
                        if(noteCount == this.noteArr.length && first){
                            this.downloadNote()
                        }
                    }
                })
            }
        },
        downloadNote(){
            console.log('下载')
            this.$.ajax({
                method:"POST",
                url:'get_note.php',
                data:this.qs({
                    user_id:this.user.id
                })
            }).then((res)=>{
                if(res.code == 0){
                    this.$store.commit('setNoteArr', res.data)
                }
            })
        },
        updateLabel(){
            this.$.ajax({
                method:"POST",
                url:'updateLabel.php',
                data:this.qs({
                    labelArr:JSON.stringify(this.labelArr),
                    user_id:this.user.id
                })
            }).then((res)=>{
                console.log(res);
            })
        },
        downloadLabel(){
            this.$.ajax({
                method:"POST",
                url:'get_label.php',
                data:this.qs({
                    user_id:this.user.id
                })
            }).then((res)=>{
                if(res.code == 0){
                    if(res.data && res.data != 'null'){
                        var newLabel = JSON.parse(res.data);
                        if(newLabel.length > this.labelArr.length){
                            this.$store.commit('setLabelArr', newLabel);
                        }else {
                            this.updateLabel()
                        }
                    }else {
                        this.updateLabel();
                    }
                }
            })
        },
        getCookie(name){
            var nameEQ = name + '='
            var ca = document.cookie.split(';') // 把cookie分割成组
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i] // 取得字符串
                while (c.charAt(0) == ' ') { // 判断一下字符串有没有前导空格
                    c = c.substring(1, c.length) // 有的话，从第二位开始取
                }
                if (c.indexOf(nameEQ) == 0) { // 如果含有我们要的name
                    return unescape(c.substring(nameEQ.length, c.length)) // 解码并截取我们要值
                }
            }
            return false
        },
        clearCookie(name){
            this.setCookie(name, "", -1);
        },
        setCookie(name, value, seconds){
            seconds = seconds || 0;   //seconds有值就直接赋值，没有为0，这个根php不一样。
            var expires = "";
            if (seconds != 0 ) {      //设置cookie生存时间
                var date = new Date();
                date.setTime(date.getTime()+(seconds*1000));
                expires = "; expires="+date.toGMTString();
            }
            document.cookie = name+"="+escape(value)+expires+"; path=/";   //转码并赋值
        },
        openResetSafe(){
            console.log('重置安全密码');
            this.$messageBox.confirm('确定要重置隐私密码吗？').then(action => {
                this.$store.commit('setLoading',true);
                var baseUrl = 'http://localhost/note/reset_safe.html'
                this.$axios({
                    method: 'post',
                    url: 'http://localhost/mailer/mail.php',
                    data: this.qs({
                        mailto:this.user.email,
                        topic:'重置密码',
                        content:"<span>请点击下面链接重置隐私密码，如果不能跳转，请复制网址到浏览器打开。</span><br>" +
                        "<a href=" + baseUrl + "?id=" + this.user.id  + ">" + baseUrl + "?id=" + this.user.id + "<a/>"
                    })
                }).then((res=>{
                    this.$store.commit('setLoading',false);
                    if(res.data.return == 0){
                        this.$toast({
                            message: '重置链接以发送至邮箱，请查收！',
                            duration: 3000
                        });
                    }else {
                        this.$toast({
                            message: '邮件发送失败！',
                            position: 'top',
                            duration: 3000
                        });
                    }
                }));
            }).catch(action=>{
                return false;
            })
        },
        feedback(){
            this.$messageBox({
                title: '提示',
                message: '请发送至邮箱：<a href="mailto:tian_shicong@163.com" style="color:rgb(13, 135, 148)">tian_shicong@163.com</a>',
                showCancelButton: false
            });
        },
        touchstart(e){
//            console.log(e);
//            console.log(e.touches[0].pageX);
            this.touchTimeStamp = e.timeStamp;
            this.touchStartX = e.touches[0].pageX;
        },
        touchmove(e){
//            console.log(e);
//            console.log(e.touches[0].pageX);
//            console.log(this.touchStartX - e.touches[0].pageX);
            this.slideLeft = (e.touches[0].pageX - this.touchStartX) < 0 ? e.touches[0].pageX - this.touchStartX : 0
        },
        touchend(e){
//            console.log(e);
            console.log(-this.slideLeft / (e.timeStamp - this.touchTimeStamp));
            var speed = -this.slideLeft / (e.timeStamp - this.touchTimeStamp);
            if((-this.slideLeft > this.slideWidth*0.4) || speed > 0.3){
                this.$store.commit('setGlobalBg');
                this.$store.commit('setSlide');
                setTimeout(()=>{
                    this.slideLeft = 0
                },350)
            }else{
                this.slideLeft = 0;
                this.isTransition = true;
                setTimeout(()=>{
                    this.isTransition = false;
                },300);
            }
        },
    },
    mounted(){
        var timer = setInterval(()=> {
            if(this.user && this.user.name){
                clearInterval(timer);
                clearTimeout(timer1);
                if(this.labelArr.length > 1){
                    this.downloadLabel()
                }else {
                    this.downloadLabel()
                }
                if(this.noteArr.length > 0){
                    this.updateNote(true);
                }else {
                    this.downloadNote()
                }
            }
        },200);
        setTimeout(()=>{
          this.setLabelNum();
        },10);
        var timer1 = setTimeout(()=>{
            clearInterval(timer)
        },5000);

        //获取侧栏宽度
        setTimeout(()=>{
            var slide = document.querySelector('#app');
            this.slideWidth = Math.ceil(slide.clientWidth * 0.7);
            console.log(this.slideWidth)
        },200)

    },
    watch:{
      usableNote: {
        handler(newVal, oldVal) {
            this.setLabelNum();
        },
        deep: true
      },
        labelArr:{
            handler(newVal, oldVal) {
                this.setLabelNum();
                if(this.user && this.user.name && this.labelArr.length > 1){
                    this.updateLabel();
                }
            },
            deep: true
        },
        update(){
          if(this.update){
              console.log('同步')
              if(this.user && this.user.name){
                  console.log('开始同步')
                  this.updateNote();
              }
              setTimeout(()=>{
                  this.$store.commit('closeUpdate')
              },500)
          }
        }

    }

  }
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
  .slidePage{
    width: 70%;
    height: 100%;
    position: fixed;
    top:0;
    left: 0;
    background-color: #fff;
    z-index: 2;
    padding:0;
  }
  .slidePage-head{
    font-size: 20px;
    font-weight: 600;
    background-color: #fbfbfb;
    padding:0 14px;
    line-height: 50px;
    height: 50px;
  }
  .slidePage-content{
    height: calc(100% - 50px);
    position: absolute;
    top:50px;
    background-color: #efefef;
    width: 100%;
    overflow: auto;
  }
  .slidePage-group{
    background-color: #fff;
    /*padding:0 18px;*/
    margin-bottom: 14px;
  }
  .group-item{
    font-size: 0;
    padding:0 18px;
  }
  .group-item>div>img{
    width: 22px;
    vertical-align: middle;
    margin-top: -1px;
  }
  .group-item>div>span{
    vertical-align: middle;
    font-size: 16px;
    margin-left: 5px;
  }
  .group-item>div>span:last-of-type{
    float: right;
  }

  .group-item-num{
    color:#666
  }
  .label-icon{
    position: relative;
    top: 1px;
    display: inline-block;
    margin-right: 3px;
  }
    .popupButton{
        padding: 28px 0;
        height: 40px;
        width: calc(100% - 40px);
    }
    .popupButton>div{
        float: left;
        width: 50%;
        text-align: center;
    }
  .popupButton>div:first-child{
      float: left;
      width: calc(50% - 1px);
      text-align: center;
      border-right: 1px solid #999;
  }
  .popupButton>div>span{
      padding: 8px 40px;
  }

  .addInput{
        border: none;
        border-bottom: 1px solid #999;
        outline: none;
        font-size: 16px;
        line-height: 24px;
        padding: 5px;
        margin-left: 14px;
        width: calc(100% - 50px)
    }
    .groupItemBox{
        width: 100%;
        margin: 0px auto;
        border-bottom: 0.5px solid rgb(202, 202, 202);
        padding: 12px 0;
    }
  .group-item:last-of-type>.groupItemBox{
      border-bottom: none;
  }
    .logout{
        color:rgb(13, 135, 148);
        float: right;
        font-size: 16px;
    }
    .transition{
        transition: left 0.3s;
    }

</style>
