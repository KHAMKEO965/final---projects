import 'dart:convert';
import 'package:http/http.dart' as http;
import '../models/train_model.dart';
import '../models/booking_model.dart';

class ApiService {
  static const String baseUrl = 'http://localhost:3000/api';

  /// ดึงรายการรถไฟทั้งหมด
  Future<List<Train>> fetchTrains() async {
    final response = await http.get(Uri.parse('$baseUrl/trains'));

    if (response.statusCode == 200) {
      final List<dynamic> jsonList = jsonDecode(response.body);
      return jsonList.map((json) => Train.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load trains');
    }
  }

  /// สร้างการจองใหม่
  Future<bool> createBooking(Booking booking) async {
    final response = await http.post(
      Uri.parse('$baseUrl/bookings'),
      headers: {'Content-Type': 'application/json'},
      body: jsonEncode(booking.toJson()),
    );

    return response.statusCode == 200 || response.statusCode == 201;
  }

  /// ดึงรายการการจองของผู้ใช้
  Future<List<Booking>> fetchMyBookings({required String userId}) async {
    final response = await http.get(
      Uri.parse('$baseUrl/bookings/user/$userId'),
    );

    if (response.statusCode == 200) {
      final List<dynamic> jsonList = jsonDecode(response.body);
      return jsonList.map((json) => Booking.fromJson(json)).toList();
    } else {
      throw Exception('Failed to load bookings');
    }
  }
}
