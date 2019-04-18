import { mount } from '@vue/test-utils'
import SignUp from '../../resources/js/pages/auth/SignUp'
import expect from 'expect'
import Vue from 'vue'
import Vuelidate from 'vuelidate'

Vue.use(Vuelidate)

describe ('SignUp', () => {
    it ('Checks if testing functionality is working.', () => {
        let wrapper = mount(SignUp);

        expect(wrapper.vm.form.email).toBe('');
    });
});
