<script>
import { ref, onMounted } from 'vue';
import $api from '../api/api.js';
import useUsers from '../composables/index.js';

export default {
    name: 'UserList',
    props: {},
    setup() {
        const isLoading = ref(false);

        const users = ref([]);

        const { addUser } = useUsers();
        const selectedUser = ref({
            id: null,
            name: null
        });

        const onChangeUser = (event) => {
            const currentUser = users.value.find(user => user.id === parseInt(event.target.value));
            selectedUser.value = currentUser;
        }

        onMounted(async () => {
            isLoading.value = true;
            const response = await $api.$users.listUsers();
            console.log('oi');

            addUser();
            // console.log(response);
            users.value = response.data;
            // console.group('mounted in userList');
            // console.log($api);
            // console.log(users);
            isLoading.value = false;
        });
        return {
            isLoading,
            users,
            selectedUser,
            onChangeUser
        }
    }
}
</script>

<template>
    <div class="user-list">
        <p v-if="isLoading">Loading..</p>
        <h1 v-if="selectedUser.name">Selected User {{ selectedUser.name }}</h1>
        <select @change="onChangeUser" v-model="selectedUser.id">
            <option v-for="user, key in users" :key="user.id" :value="user.id">{{ `${user.name} - ${user.id}` }}</option>
        </select>


    </div>
</template>
