import PhotoSelector from './components/PhotoSelector';
import setup_delete_confirmers from './deleteConfirmer';
import Vue from 'vue';

setup_delete_confirmers();

const root = document.getElementById('photo-selector');

if(root !== null){
    /* eslint-disable no-new */
    new Vue({
        el: '#photo-selector',
        template: '<PhotoSelector/>',
        components: { PhotoSelector }
    });
}
