import { reactive } from "vue";

const state = reactive({})
   const addUser = () => {
    console.log('foo');
   }
const useUsers = () => {


   return {
    addUser
   }
}

export default useUsers;