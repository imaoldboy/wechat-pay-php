const CoffeeMachine = require('../models/coffeeMachine')
let id = "5981ef1bc9afb57af38f3ff9"
let body = {
    serialNo: '222'
}
CoffeeMachine.findByIdAndUpdate(id, {$set: body}, {new: true})
    .then(document => console.log(document))
    .catch(error => console.log(error))
