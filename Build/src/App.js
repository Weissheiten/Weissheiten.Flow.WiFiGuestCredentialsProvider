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
                                { header: "username", lookupproperty: "username", sortfunc:(a, b) => a.username.localeCompare(b.username), key: "username"},
                                { header: "requesttime", lookupproperty: "requesttime", sortfunc:(a, b) => a.requesttime.localeCompare(b.requesttime), key: "requesttime"},
                                { header: "validitymin", lookupproperty: "validitymin", sortfunc:(a, b) => a.validitymin < b.validitymin, key: "validitymin"},
                                { header: "outlet", lookupproperty: "outlet.name", sortfunc: (a, b) => a.outlet.name.localeCompare(b.outlet.name), key: "outlet.name"}
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
