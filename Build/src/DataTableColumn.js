import React, { Component } from 'react';

class DataTableColumn extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        let buttonStyles =  {
            padding:'2pt',
            height:'auto',
            lineHeight:'8pt',
            marginBottom: '4pt',
            marginLeft: '2pt'
        };

        return (
            <th className="statistics-table-column">{this.props.datacolumn.header}
                <button onClick={(i, sortasc) => this.props.handlesortclick(this.props.datacolumn, true)} style={buttonStyles}><i className="icon-arrow-up"></i></button>
                <button onClick={(i, sortasc) => this.props.handlesortclick(this.props.datacolumn, false)} style={buttonStyles}><i className="icon-arrow-down"></i></button>
            </th>
        );
    }
}

export default DataTableColumn;