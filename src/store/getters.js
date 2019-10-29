/**
 * Created by administrator on 2019/4/24.
 */
const getters = {
  usableNote(state){
    return state.noteArr.filter(note=>note.status==1 && note.islock == 0)
  },
  collectNote(state){
    return state.noteArr.filter((note)=>{
      return note.status == 1 && note.collect == true
    })
  },
  labelNote:(state)=>(n)=>{
      if(n){
        return state.noteArr.filter((note)=>{
          return note.status == 1 && note.label == n && note.islock == 0;
        })
      }else {
        return state.noteArr.filter((note)=>{
          return note.status == 1 && note.islock == 0;
        })
      }
  },
  usableLabel(state){
    return state.labelArr.filter(label=>label.status==1)
  },
    lockNote(state){
        return state.noteArr.filter((note)=>{
            return note.status == 1 && note.islock == true
        })
    },
}

export default getters
