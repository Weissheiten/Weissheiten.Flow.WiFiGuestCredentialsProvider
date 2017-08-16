import React, { Component } from 'react';
import DataRequests from './DataRequests'
import DataTable from './DataTable';

class App extends Component {
    constructor(props){
        super(props);
        this.state = { datatables:[] }
    }

    render() {
        return (
          <div className="App">
              {this.state.datatables.map(function(dt){
                    return <DataTable key={dt.key} datatable={dt}/>
              })}
          </div>
        );
    }

    handleSortClick(sortProperty){
        var vouchersSorted = this.state.vouchers.slice(0);

        vouchersSorted.sort(
            (a, b) => a.outlet.name.localeCompare(b.outlet.name)
        );

        this.setState({
            vouchers: vouchersSorted
        });
    }

    componentDidMount(){
        var that = this;

        var myRequest = "/neos/management/WiFiVoucher/Report/getVoucherRequestEntries.json";
        fetch(myRequest, {
            credentials: 'same-origin'
        }).then(function(response) {
            var contentType = response.headers.get("content-type");
            if(contentType && contentType.includes("application/json")) {
                return response.json();
            }
            throw new TypeError("Oops, we haven't got JSON!");
        })
            .then(function(json) {
                json.map(function(nodes){
                    if(nodes.requesttime==null) {
                        nodes.outlet = { name: "n/a" }
                        nodes.requesttime = "n/a";
                    }
                });

                that.setState({
                    datatables: [
                        {
                            key: 'main',
                            dataentries: json,
                            columns: [
                                { header: "username", lookupproperty: "Username", key: "username"},
                                { header: "requesttime", lookupproperty: "Requesttime", key: "requesttime"},
                                { header: "validitymin", lookupproperty: "Validitymin", key: "validitymin"},
                                { header: "outlet", lookupproperty: "Outlet", key: "outlet"}
                            ]
                        }]
                })
            })
            .catch(function(error) { console.log(error); });
    }

    componentWillUnmount(){
        this.serverRequest.abort();
    }
}

export default App;
