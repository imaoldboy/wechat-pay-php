const mongoose = require('mongoose')

const uri = 'mongodb://localhost:27017/coffee'
const options = {
    useMongoClient: true
}

mongoose.Promise = global.Promise
mongoose
    .connect(uri, options)
    .then(db => console.log('connect mongodb success!'))
    .catch(error => console.log('error connect to db'+error))

module.exports = mongoose
