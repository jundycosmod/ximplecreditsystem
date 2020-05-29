import AppForm from '../app-components/Form/AppForm';

Vue.component('creditor-form', {
    mixins: [AppForm],
    data: function() {
        return {
            form: {
                first_name:  '' ,
                middle_name:  '' ,
                last_name:  '' ,
                is_active:  false ,
                
            }
        }
    }

});