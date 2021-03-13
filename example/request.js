var request = require('request');

var payload = {
  "username": "username", 
  "password": "password"
};

// You can try others methods (see documentation)
var url = 'http://URI/api/search/';

var options = {
  method: 'POST',
  body: payload,
  json: true,
  url: url
}

request(options, function (error, response, body) {
  console.log('error:', error);
  console.log('statusCode:', response && response.statusCode);
  console.log('body:', body);
});