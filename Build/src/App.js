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
                        nodes.outlet = { name: "no outlet" }
                        nodes.requesttime = "unrequested";
                    }
                });

                that.setState({
                    datatables: [
                        {
                            key: 'main',
                            dataentries: json,
                            columns: [
                                {
                                    header: "Username",
                                    lookupproperty: "username",
                                    sortfunc:(a, b) => a.username.localeCompare(b.username),
                                    sortfuncdesc: (a, b) => a.username.localeCompare(b.username)*-1, key: "username",
                                    key: "username"
                                },
                                {
                                    header: "Requesttime",
                                    lookupproperty: "requesttime",
                                    sortfunc:(a, b) => a.requesttime.localeCompare(b.requesttime),
                                    sortfuncdesc:(a, b) => a.requesttime.localeCompare(b.requesttime)*-1, key: "requesttime",
                                    groupBy: function(arr){
                                        return arr.reduce(function(outarr, item){

                                            let val = (item.requesttime=="unrequested") ? "unrequested" : new Date(item.requesttime).getUTCFullYear() + "-" + (new Date(item.requesttime).getUTCMonth()+1);

                                            if(!(val in outarr)){
                                                outarr[val] = {
                                                    entries: [],
                                                    count: 0,
                                                    name: val
                                                };
                                            }

                                            outarr[val].entries.push(item);
                                            return outarr;
                                        }, [])
                                    },
                                    key: "requesttime"
                                },
                                {
                                    header: "Validity in min",
                                    lookupproperty: "validitymin",
                                    sortfunc:(a, b) => a.validitymin < b.validitymin,
                                    sortfuncdesc:(a, b) => a.validitymin > b.validitymin,
                                    groupBy: function(arr){
                                        return arr.reduce(function(outarr, item){

                                            let val = item.validitymin;

                                            if(!(val in outarr)){
                                                outarr[val] = {
                                                    entries: [],
                                                    count: 0,
                                                    name: val
                                                };
                                            }

                                            outarr[val].entries.push(item);
                                            return outarr;
                                        }, [])
                                    },
                                    key: "validitymin"
                                },
                                {
                                    header: "Outletname",
                                    lookupproperty: "outlet.name",
                                    sortfunc: (a, b) => a.outlet.name.localeCompare(b.outlet.name),
                                    sortfuncdesc: (a, b) => a.outlet.name.localeCompare(b.outlet.name)*-1,
                                    groupBy: function(arr){
                                        return arr.reduce(function(outarr, item){

                                            let val = item.outlet.name;

                                            if(!(val in outarr)){
                                                outarr[val] = {
                                                    entries: [],
                                                    count: 0,
                                                    name: val
                                                };
                                            }

                                            outarr[val].entries.push(item);
                                            return outarr;
                                        }, [])
                                    },
                                    key: "outlet.name"
                                }
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
