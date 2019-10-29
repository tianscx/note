/**
 * Created by administrator on 2019/4/10.
 */
const state = {
    user: null, // 用户信息
    slideShow:false,
    globalBg:false,
    globalBgColor:'none',
    showMore:false,
    colors:['#fb2a2d',
      '#ff7300',
      '#FFBD00',
      '#48CB47',
      '#00BFC9',
      '#00A9EE',
      '#8A2BE1',
      '#3E56EA',
      '#FF9797',
      '#FF60AF',
      '#B15BFF',
      '#4EFEB3',
      '#AD5A5A',
      '#A5A552',
      '#AE57A4'],
    labelArr:[],
    noteArr:[],
    showType:1,
    filterType:'all',//all:全部   collect：收藏  lock:加密   其他数字：标签id,
    openSearch:false,
    isLoading:false,
    update:false,
    device_id:'',
    showPage:true,
    globalBgOpacity:1
}

export default state
