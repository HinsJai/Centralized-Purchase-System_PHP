from flask import Flask, request, jsonify
from flask_cors import CORS

app = Flask(__name__)
CORS(app)  # Enable CORS for all routes

@app.route('/api/discountCalculator', methods=['GET'])
def discount_calculator():
    total_order_amount = float(request.args.get('TotalOrderAmount'))
    
    if total_order_amount >= 10000:
        discount_rate = 0.13
    elif total_order_amount >= 5000:
        discount_rate = 0.06
    elif total_order_amount >= 3000:
        discount_rate = 0.03
    else:
        discount_rate = 0
    
    new_order_amount = total_order_amount * (1 - discount_rate)
    
    response = {
        'DiscountRate': discount_rate,
        'NewOrderAmount': new_order_amount
    }
    
    return jsonify(response)

if __name__ == '__main__':
    app.run('127.0.0.1',port=8080)