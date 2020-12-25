import React from 'react';
import './States.css';

/**
 * Define States, a React componment of CS142 project #4 problem #2.  The model
 * data for this view (the state names) is available
 * at window.cs142models.statesModel().
 */
class States extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      searchContent: "",
      data: window.cs142models.statesModel()
    };

    this.handleChangeSearch = e => this.setState({ searchContent: e.target.value });
  }

  getSearchResults(searchContent) {
    searchContent = searchContent.toLowerCase();
    const listItems = [];

    for (const stateName of this.state.data) {
      if (stateName.toLowerCase().includes(searchContent)) {
        listItems.push(<li className="cs142-states-li" key={stateName}>{stateName}</li>);
      }
    }

    if (listItems.length > 0) {
      return (
        <div>
          <p className="cs142-states-resultHeader">Search results is:</p>
          <ul className="cs142-states-ul">{listItems}</ul>
        </div>
      );
    } else {
      return (
        <div>
          <p className="cs142-states-resultHeader">Sorry, no results.</p>
        </div>
      );
    }
  }

  render() {
    return (
      <div className="cs142-states-p2">
        <p className="cs142-states-instructor">Please enter strings to search the state names.</p>
        <input type="text" value={this.state.searchContent} onChange={this.handleChangeSearch} />
        {this.getSearchResults(this.state.searchContent)}
      </div>
    );
  }
}

export default States;
