(function() {

    var website = angular.module("modules.website");

    website.controller('HomeController', ['$scope', '$http', 'ngToast', function($scope, $http, ngToast) {

        $scope.is_running_requests = false;

        $scope.submitChangeDomainForm = function (formData) {

            $scope.is_running_requests = true;

            $http.post(baseUrl, formData).then(function (response) {

                $scope.model = {};

                $scope.sql = response.data.sql.join("\n");

                $scope.is_running_requests = false;

                ngToast.create({
                    content: 'SQL gerado com sucesso!'
                });

            }, function (response) {

                for (var index in response.data) {
                    ngToast.create({
                        className: 'danger',
                        content: index + ': ' + response.data[index]
                    });
                }

                $scope.is_running_requests = false;
            });
        };

    }]);

})();
