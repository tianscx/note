<template>
  <div id="app"  v-if="showPage">
    <transition :name="transitionName" mode="out-in">
      <router-view class="Router"></router-view>
    </transition>
    <transition name="slide-fade">
      <slide-pane v-show="slidePaneShow"></slide-pane>
    </transition>
    <div v-if="globalBg" class="globalBg" @click="toggleGlobalBg" :style="{ backgroundColor: globalBgColor,opacity:globalBgOpacity}"></div>
      <span class="loading" v-if="isLoading">
          <mt-spinner  type="snake" color="#26a2ff"></mt-spinner>
      </span>
  </div>
</template>

<script>
  import slidePane from './components/slidePane.vue'
export default {
  name: 'App',
  components:{
      slidePane,
  },
  computed:{
      slidePaneShow(){
          return this.$store.state.slideShow;
      },
      globalBgColor(){
        return this.$store.state.globalBgColor;
      },
      globalBg(){
        return this.$store.state.globalBg;
      },
      isLoading(){
          return this.$store.state.isLoading
      },
      deviceId(){
          return this.$store.state.device_id
      },
      showPage(){
          return this.$store.state.showPage
      },
      globalBgOpacity(){
          return this.$store.state.globalBgOpacity
      }
  },
  data(){
      return{
        transitionName:''
      }
  },
  methods:{
    toggleSlide(){
      this.$store.commit('setSlide');
      this.$store.commit('setGlobalBg',1);
    },
    toggleGlobalBg(n){
      this.$store.commit('setGlobalBg',n);
      if(this.slidePaneShow){
        this.$store.commit('setSlide');
      }
      if(this.$store.state.showMore){
        this.$store.commit('setShowMore');
      }
    }
  },
  watch:{
      globalBgColor(){
//          console.log(this.globalBgColor)
      },
      '$route' (to, from) {
          console.log(to.path)
        if(to.path == '/noteList'){
          this.transitionName = 'slide-right';
        }else{
          this.transitionName = 'slide-left';
        }
//        console.log(this.transitionName)
      }
  },
  mounted(){
      //设置标签
      setTimeout(()=>{
          if(localStorage.getItem('labelArr')){
              this.$store.commit('setLabelArr',JSON.parse(localStorage.getItem('labelArr')))
          }else {
              var labelArr = [{
                  value:'0',
                  label:'未标签',
                  color:'#333',
                  status:1,
                  updateTime:(new Date()).valueOf(),
                  device_id:this.deviceId
              }];
//          localStorage.setItem('labelArr', JSON.stringify(labelArr));
              this.$store.commit('setLabelArr',labelArr)
          }
      })


      //设置笔记列表
      if(localStorage.getItem('noteArr')){
        this.$store.commit('setNoteArr',JSON.parse(localStorage.getItem('noteArr')))
      }

      //视图模式
      if(localStorage.getItem('showType')){
          this.$store.commit('setShowType1',localStorage.getItem('showType'));
      }

      //设置用户
      if(localStorage.getItem('user')){
          this.$.ajax({
              method:"POST",
              url:'get_user.php',
              data:this.qs({
                  email:JSON.parse(localStorage.getItem('user')).email
              })
          }).then((res)=>{
              console.log(res)
              if(res.code == 0){
                  if(res.data.password == JSON.parse(localStorage.getItem('user')).password){
                      this.$store.commit('setUserSession',res.data);
                  }
              }
          })
      }

      //设置设备标识
      if(localStorage.getItem('device_id')){
          this.$store.commit('setDevice',localStorage.getItem('device_id'))
      }else {
          var device_id = Math.floor(Math.random() * 10000);
          this.$store.commit('setDevice',device_id)
      }

      //同步数据库
      this.$.ajax({
          method:"GET",
          url:'isUpdate.php'
      }).then((res)=>{
          var mark = (new Date()).valueOf() - res * 1000 > 3600 * 1000 * 24;
          if(mark){
              this.$.ajax({
                  method:"GET",
                  url:'beifen.php'
              }).then((res)=>{
                  console.log(res)
              })
          }
      })
  }
}
</script>

<style>
body,html{
  margin:0;
    padding:0;
  font-family: "Helvetica Neue",Helvetica,"PingFang SC","Hiragino Sans GB","Microsoft YaHei","微软雅黑",Arial,sans-serif;
    overflow: hidden;
}
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
.mintui-more,.mintui-back{
  font-size: 20px!important;
}
  .mint-header{
    background-color: #f8f8f8!important;
    color: #1c1c1c!important;
    font-size: 20px!important;
    font-weight: 600!important;
      height:50px!important;
  }
.mint-header-title {
  position: absolute!important;
  left: 40px!important;
  top: 3px!important;
  font-size: 18px!important;
    height: 18px!important;
    padding-top:2px;
}
  .globalBg{
    background-color: rgba(0,0,0,0.12);
    width: 100%;
    height: 100%;
    position: fixed;
    top: 0;
    left: 0;
    transition: all 0.3s;
  }
  /*侧滑面板*/
.slide-fade-enter-active {
  transition: all .3s ease;
}
.slide-fade-leave-active {
  transition: all .3s ease;
}
.slide-fade-enter, .slide-fade-leave-to
  /* .slide-fade-leave-active for below version 2.1.8 */ {
  transform: translateX(-100%);
}
.moreList{
  position: absolute;
  top: 40px;
  right: 0;
  z-index: 2;
  background-color: #fff;
  padding:15px 15px;
  box-shadow: 0 0 8px 0 rgba(0,0,0,0.2);
  font-size: 14px;
  color: #333;
  border-radius: 4px;
}
  .moreItem{
    padding-top:12px;
    padding-right:24px;
  }
  .moreItem:first-of-type{
    border-bottom: 0.5px solid #999;
    padding-bottom:12px;
    padding-top:0;
  }
/*页面切换动画*/
.Router {
  position: absolute;
  width: 100%;
  transition: all 0.2s ease;
  top: 0;
}
.slide-left-enter,
.slide-right-leave-active {
  opacity: 0;
  -webkit-transform: translate(100%, 0);
  transform: translate(50px, 0);
}

.slide-left-leave-active,
.slide-right-enter {
  opacity: 0;
  -webkit-transform: translate(-100%, 0);
  transform: translate(-50px, 0);
}
  .toast{
    z-index: 9999;
  }
.clickItem:active{
    background-color: #efefef;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    border-radius: 6px;
}
    .labelName{
        max-width: 200px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .loading{
        position: fixed;
        left: 0;
        right: 0;
        top:0;
        bottom: 0;
        margin:auto;
        display: flex;
        justify-content: center;
        align-items: center;
    }

  .vue-html5-editor .toolbar{
    position: fixed!important;
    bottom: 0!important;
    width: calc(100% - 14px)!important;
  }
  .vue-html5-editor .toolbar>ul{
    display: flex;
    justify-content: space-around;
  }
    .vue-html5-editor>.toolbar>ul>li{
        line-height: 48px!important;
        font-size: 18px!important;
    }
.vue-html5-editor>.toolbar>.dashboard{
    top:auto!important;
    bottom: 45px;
}
.note-title-line ul,.note-title-line ol,.note-title-line h1,.note-title-line h2,.note-title-line h3,.note-title-line h4,.note-title-line h5,.note-title-line h6,.note-title-line b,.note-title-line i,.note-title-line div,.note-title-line font,.note-title-line u,.note-title-line strike,.note-title-line sup,.note-title-line sub,.note-title-line span{
    padding:0;
    margin:0;
    font-size: 16px!important;
    color: #444!important;
    font-weight: 400;
    text-align: left!important;
    font-style:normal;
    background-color: rgba(255,255,255,0)!important;
    line-height: 28px!important;
    text-decoration: none;
    vertical-align:middle!important;
}
.note-title-line font{
    margin-top: -4px;
    display: inline-block;
}
    .vue-html5-editor .content{
        height: 100%;
    }
.note-title-line pre{
    margin-top:0!important;
    font-family:"Helvetica Neue",Helvetica,"PingFang SC"
}
.vue-html5-editor>.content{
    padding-bottom: 26px!important;
}
</style>
