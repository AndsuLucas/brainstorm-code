import $http from "..";

const $users = {
    async listUsers() {
        return await $http.get('/users');
    }
}


export default $users;