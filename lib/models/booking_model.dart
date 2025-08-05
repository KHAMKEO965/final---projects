class Booking {
  final String? id; // Nullable for new bookings (ID assigned by backend)
  final String userId;
  final String trainId;
  final String trainName; // Added for display convenience
  final String departureStation;
  final String arrivalStation;
  final String departureTime;
  final String arrivalTime;
  final int numberOfTickets;
  final double totalPrice;
  final DateTime bookingDate; // Store as DateTime for better handling

  const Booking({
    this.id,
    required this.userId,
    required this.trainId,
    required this.trainName,
    required this.departureStation,
    required this.arrivalStation,
    required this.departureTime,
    required this.arrivalTime,
    required this.numberOfTickets,
    required this.totalPrice,
    required this.bookingDate,
  });

  // Factory constructor to create a Booking object from a JSON map
  factory Booking.fromJson(Map<String, dynamic> json) {
    return Booking(
      id: json['id'] as String?,
      userId: json['userId'] as String,
      trainId: json['trainId'] as String,
      trainName: json['trainName'] as String,
      departureStation: json['departureStation'] as String,
      arrivalStation: json['arrivalStation'] as String,
      departureTime: json['departureTime'] as String,
      arrivalTime: json['arrivalTime'] as String,
      numberOfTickets: json['numberOfTickets'] as int,
      totalPrice: (json['totalPrice'] as num).toDouble(),
      bookingDate: DateTime.parse(json['bookingDate'] as String), // Parse string to DateTime
    );
  }

  // Method to convert a Booking object to a JSON map
  Map<String, dynamic> toJson() {
    return {
      'userId': userId,
      'trainId': trainId,
      'trainName': trainName,
      'departureStation': departureStation,
      'arrivalStation': arrivalStation,
      'departureTime': departureTime,
      'arrivalTime': arrivalTime,
      'numberOfTickets': numberOfTickets,
      'totalPrice': totalPrice,
      'bookingDate': bookingDate.toIso8601String(), // Convert DateTime to ISO 8601 string
    };
  }
}