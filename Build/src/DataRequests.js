class DataRequests {
    static getVoucherInfo(){
        var myRequest = "/neos/management/WiFiVoucher/Report/getVoucherRequestEntries.json";

        let response = "error";

        fetch(myRequest).then(function(response) {
            var contentType = response.headers.get("content-type");
            if(contentType && contentType.includes("application/json")) {
                return response.json();
            }
            throw new TypeError("Oops, we haven't got JSON!");
        })
            .then(function(json) { response =  json; })
            .catch(function(error) { console.log(error); });

        return response;
    }
}

export default DataRequests;



