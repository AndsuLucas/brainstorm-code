import { mount, flushPromises } from '@vue/test-utils';
import UserList from './UserList.vue';
import $api from '../api/api.js';
import usersResponseMock from './mocks/usersResponseMock.js';
import useUsers from '../composables/index.js';

// mock entire module
// jest.mock('../composables/index.js', () => {
//     return {
//         __esModule: true,
//         default: jest.fn(() => {
//             return {addUser: jest.fn(() => console.log('bar2'))}
//         })
//     }
// });

const addUserSpy  = jest.spyOn(useUsers(), 'addUser');

describe('Test UserList', () => {
    afterEach(() => {
        jest.restoreAllMocks();
    });

    test('This test will mock $users', async () => {
        jest.spyOn($api.$users, 'listUsers').mockResolvedValue({ data: usersResponseMock });

        const wrapper = await mount(UserList);
        await wrapper.vm.$nextTick();
        // console.log(wrapper.html());
    });

    test('Check if keep mocking', async () => {
        const wrapper = await mount(UserList);
        await wrapper.vm.$nextTick();

        expect(wrapper.vm.users.length).toBe(0); // ok thanks for restoreAllMocks();
    });

    test.only('Test mock composable', async () => {
        const addUserSpy = jest.spyOn(useUsers(), 'addUser'); // only spy objects. This not work!
        const {addUser} = useUsers();
        jest.spyOn($api.$users, 'listUsers').mockResolvedValue({ data: usersResponseMock });


        const wrapper = await mount(UserList);
        wrapper.vm.$forceUpdate();
        flushPromises();

        expect(addUserSpy).toHaveBeenCalled(); // dont work. Spies only work in objects
    }); 

});