import React, { Component } from 'react';

class DataTableEntry extends Component {
    constructor(props) {
        super(props);
    }

    // add a "resolve" prototype in order to access nested properties dynamically
    resolve(path, obj) {
        return path.split('.').reduce(function(prev, curr) {
            return prev ? prev[curr] : undefined
        }, obj || self)
    }

    render() {
        var that = this;
        let dataentries = this.props.entryvalues;
        return (
            <tr className="statistics-table-entry">
                {this.props.columns.map(function(col){
                    return <td key={col.lookupproperty}>{that.resolve(col.lookupproperty,dataentries)}</td>
                })}
            </tr>
        );
    }
}

export default DataTableEntry;