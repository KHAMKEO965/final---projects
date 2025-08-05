class Train {
  final String id; // Assuming an ID for each train
  final String trainName;
  final String departure;
  final String arrival;
  final String departureTime;
  final String arrivalTime;
  final int availableSeats;
  final double price;

  const Train({
    required this.id,
    required this.trainName,
    required this.departure,
    required this.arrival,
    required this.departureTime,
    required this.arrivalTime,
    required this.availableSeats,
    required this.price,
  });

  // Factory constructor to create a Train object from a JSON map
  factory Train.fromJson(Map<String, dynamic> json) {
    return Train(
      id: json['id'] as String,
      trainName: json['trainName'] as String,
      departure: json['departure'] as String,
      arrival: json['arrival'] as String,
      departureTime: json['departureTime'] as String,
      arrivalTime: json['arrivalTime'] as String,
      availableSeats: json['availableSeats'] as int,
      price: (json['price'] as num).toDouble(), // Handle int or double from JSON
    );
  }

  // Method to convert a Train object to a JSON map
  Map<String, dynamic> toJson() {
    return {
      'id': id,
      'trainName': trainName,
      'departure': departure,
      'arrival': arrival,
      'departureTime': departureTime,
      'arrivalTime': arrivalTime,
      'availableSeats': availableSeats,
      'price': price,
    };
  }
}