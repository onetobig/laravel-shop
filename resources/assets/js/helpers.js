require('sweetalert');
import _ from 'lodash'

function swalError(errors) {
    let html = '<div>';
    _.each(errors, function (errors) {
      _.each(errors, function (error) {
          html += error + '<br>';
      });
    });
    html += '</div>';
    swal({content: $(html)[0], icon: 'error'});
}

function systemError() {
    swal('系统错误', '', 'error');
}

export {
    systemError,
    swalError,
}