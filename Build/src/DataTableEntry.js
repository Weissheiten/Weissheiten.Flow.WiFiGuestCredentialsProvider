import React, { Component } from 'react';

class DataTableEntry extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let dataentries = this.props.entryvalues;
        return (
            <tr className="statistics-table-entry">
                {this.props.columns.map(function(col){
                    return <td>{dataentries[col]}</td>
                })}
            </tr>
        );
    }
}

export default DataTableEntry;