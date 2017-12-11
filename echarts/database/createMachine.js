const CoffeeMachine = require('../models/coffeeMachine')

const machine1 = new CoffeeMachine({
    description: "third coffee",
    location: "Pku",
    customerID: "",
    ipAddress: "", 
    maintainerID: "",
    updateUrl: "",
    qrCode: "",
    remarks: "",
    cups: 0,
    status: 1,
    serialNo: "1234"
})

machine1.save()
    .then(document => console.log(document))
    .catch(error => console.log(error))
