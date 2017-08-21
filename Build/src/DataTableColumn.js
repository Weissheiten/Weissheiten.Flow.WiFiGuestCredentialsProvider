import React, { Component } from 'react';

class DataTableColumn extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        return (
            <th className="statistics-table-column">{this.props.datacolumn.header}<button onClick={(i) => this.props.handlesortclick(this.props.datacolumn)}>S</button></th>
        );
    }

    // <button onClick={() => this.props.sortClick(this.props.valuefield)}>S</button>
}

export default DataTableColumn;