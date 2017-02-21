(function() {
  'use strict';
  App.factory('uiSelectService', ['$resource',
    function($resource) {
      return $resource('http://api.rottentomatoes.com/api/public/v1.0/movies.json', {}, {
        get: {
          method: 'JSONP',
          params: {
            callback: 'JSON_CALLBACK',
            apikey: '2evps5bjgnwus5deeanv8gk2' //please do not use this key, you can get your own
          }
        }
      });
    }
  ])
})();
