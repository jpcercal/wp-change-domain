(function() {

    var website = angular.module("modules.website", []);

    website.config(['cfpLoadingBarProvider', function(cfpLoadingBarProvider) {
        cfpLoadingBarProvider.includeSpinner = false;
        cfpLoadingBarProvider.includeBar     = true;
    }]);

    website.config(['ngToastProvider', function(ngToastProvider) {
        ngToastProvider.configure({
            animation: 'slide',
            verticalPosition: 'bottom'
        });
    }]);



})();
