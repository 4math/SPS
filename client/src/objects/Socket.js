export default class SocketData {
    /**
     * @param {object} param0 
     * @param {number} param0.id
     * @param {string} param0.name
     * @param {string} param0.description
     * @param {number} param0.switch_state
     */
    constructor({id, name, description, switch_state}) {
        this.id = id;
        this.name = name;
        this.description = description;
        // change the type of state from number to boolean
        this.switch_state = !!switch_state;
    }
}