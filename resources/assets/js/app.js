require('./bootstrap');

require('./deleteConfirmer');

window.Vue = require('vue');

import PhotoSelector from './components/PhotoSelector';

Vue.config.productionTip = false;


if($('#photo-selector').length){
    /* eslint-disable no-new */
    new Vue({
        el: '#photo-selector',
        template: '<PhotoSelector/>',
        components: { PhotoSelector }
    });
}

