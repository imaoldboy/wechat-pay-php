const CoffeeMachine = require('../models/coffeeMachine')

CoffeeMachine.find()
    .then(document => console.log(document))
    .catch(error => console.log(error))
