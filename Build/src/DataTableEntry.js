import React, { Component } from 'react';

class DataTableEntry extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <tr className="statistics-table-entry">
                <td>{this.props.entryvalues.username}</td>
                <td>{this.props.entryvalues.requesttime}</td>
                <td>{this.props.entryvalues.outlet.name}</td>
            </tr>
        );
    }
}

export default DataTableEntry;