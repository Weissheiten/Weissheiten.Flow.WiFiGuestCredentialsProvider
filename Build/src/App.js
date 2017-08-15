import React, { Component } from 'react';
import DataRequests from './DataRequests'
import DataTable from './DataTable';

class App extends Component {
    constructor(props){
        super(props);
        this.state = {vouchers: []};
    }

    render() {
        return (
          <div className="App">
              <DataTable entries={this.state.vouchers} sortClick={() => this.handleSortClick("demo")} />
          </div>
        );
    }

    handleSortClick(property){
        var vouchersSorted = this.state.vouchers.slice(0);
        vouchersSorted.sort(function(a, b){
            // some sorting
        });
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
                that.setState({ vouchers: json })
            })
            .catch(function(error) { console.log(error); });
    }

    componentWillUnmount(){
        this.serverRequest.abort();
    }
}

export default App;
