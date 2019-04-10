import { mount } from '@vue/test-utils';
import SignUp from '../../resources/js/pages/auth/SignUp';
import expect from 'expect';

describe ('SignUp', () => {
    it ('Checks if testing functionality is working.', () => {
        let wrapper = mount(SignUp);

        expect(wrapper.vm.form.email).toBe('');
    });
});
