import 'package:flutter/material.dart';

// 1. Data Model for a Booking
class Booking {
  final String id;
  final String serviceName;
  final DateTime bookingDate;
  final String status;

  Booking({
    required this.id,
    required this.serviceName,
    required this.bookingDate,
    required this.status,
  });
}

// 2. A Widget to display a single booking
class BookingCard extends StatelessWidget {
  final Booking booking;

  const BookingCard({Key? key, required this.booking}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
      child: ListTile(
        leading: const Icon(Icons.calendar_today),
        title: Text(booking.serviceName),
        subtitle: Text(
          'Date: ${booking.bookingDate.toLocal().toString().split(' ')[0]}\n'
          'Status: ${booking.status}',
        ),
        trailing: const Icon(Icons.arrow_forward_ios),
        onTap: () {
          // TODO: Navigate to a detailed booking screen
          print('Tapped on booking: ${booking.id}');
        },
      ),
    );
  }
}

// 3. The main screen widget
class MyBookingsScreen extends StatefulWidget {
  const MyBookingsScreen({Key? key}) : super(key: key);

  @override
  _MyBookingsScreenState createState() => _MyBookingsScreenState();
}

class _MyBookingsScreenState extends State<MyBookingsScreen> {
  bool _isLoading = true;
  List<Booking> _bookings = [];

  @override
  void initState() {
    super.initState();
    _fetchBookings();
  }

  Future<void> _fetchBookings() async {
    // Simulate fetching data from a backend
    await Future.delayed(const Duration(seconds: 2));

    // Example mock data
    final mockBookings = [
      Booking(
        id: '1',
        serviceName: 'Haircut',
        bookingDate: DateTime.now().add(const Duration(days: 2, hours: 10)),
        status: 'Confirmed',
      ),
      Booking(
        id: '2',
        serviceName: 'Massage',
        bookingDate: DateTime.now().add(const Duration(days: 5, hours: 15)),
        status: 'Pending',
      ),
      Booking(
        id: '3',
        serviceName: 'Manicure',
        bookingDate: DateTime.now().add(const Duration(days: 10, hours: 9)),
        status: 'Confirmed',
      ),
    ];

    setState(() {
      _bookings = mockBookings;
      _isLoading = false;
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('My Bookings'),
      ),
      body: _isLoading
          ? const Center(
              child: CircularProgressIndicator(),
            )
          : _bookings.isEmpty
              ? const Center(
                  child: Text('You have no upcoming bookings.'),
                )
              : ListView.builder(
                  itemCount: _bookings.length,
                  itemBuilder: (context, index) {
                    final booking = _bookings[index];
                    return BookingCard(booking: booking);
                  },
                ),
    );
  }
}