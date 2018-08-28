let zone_field = document.querySelector("#zone"); //select zone field
let state_select = document.querySelector("#state-select"); //select state field
let town_select = document.querySelector("#lga-select");

//data here has been declared in lga_data.js
let states_list = data.map(res => res.state.name); //get states_list

// get list of lgas
function lga_list(state_name) {
  let state_item;
  for (let state of data) {
    if (state.state.name == state_name) {
      state_item = state.state;
    }
  }
  if (state_item) {
    return state_item.locals.map(res => {
      return res.name;
    });
  }
  return null;
}
for (let state of states_list) {
  state_select.options[state_select.options.length] = new Option(state, state);
}

//update town Select field
function updateTownSelect(state_name) {
  // console.log(state_name)
  town_list = lga_list(state_name);
  town_select.options.length = 1;

  if (zone_field) {
    updateZone(state_name);
  }

  if (!town_list) {
    return;
  }

  for (let town of town_list) {
    town_select.options[town_select.options.length] = new Option(town, town);
  }

  return;
}
