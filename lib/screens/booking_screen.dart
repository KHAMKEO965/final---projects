import 'package:flutter/material.dart';
import '../models/train_model.dart';
import '../models/booking_model.dart'; // Assuming you have this model
import '../services/api_service.dart'; // Assuming you have this service

class BookingScreen extends StatefulWidget {
  final Train selectedTrain; // The train selected from a previous screen

  const BookingScreen({super.key, required this.selectedTrain});

  @override
  State<BookingScreen> createState() => _BookingScreenState();
}

class _BookingScreenState extends State<BookingScreen> {
  int _numberOfTickets = 1; // Default to 1 ticket
  final ApiService _apiService = ApiService();
  bool _isLoading = false;
  String? _errorMessage;

  // You might get the userId from an authentication service or shared preferences
  final String _currentUserId = 'user123'; // Placeholder User ID

  void _incrementTickets() {
    setState(() {
      if (_numberOfTickets < widget.selectedTrain.availableSeats) {
        _numberOfTickets++;
      } else {
        _errorMessage = 'Maximum available seats reached.';
      }
    });
  }

  void _decrementTickets() {
    setState(() {
      if (_numberOfTickets > 1) {
        _numberOfTickets--;
        _errorMessage = null; // Clear error if decreasing
      }
    });
  }

  Future<void> _bookTrain() async {
    setState(() {
      _isLoading = true;
      _errorMessage = null;
    });

    try {
      final totalPrice = _numberOfTickets * widget.selectedTrain.price;

      final booking = Booking(
        userId: _currentUserId,
        trainId: widget.selectedTrain.id,
        trainName: widget.selectedTrain.trainName,
        departureStation: widget.selectedTrain.departure,
        arrivalStation: widget.selectedTrain.arrival,
        departureTime: widget.selectedTrain.departureTime,
        arrivalTime: widget.selectedTrain.arrivalTime,
        numberOfTickets: _numberOfTickets,
        totalPrice: totalPrice,
        bookingDate: DateTime.now(), // Current booking date/time
      );

      final success = await _apiService.createBooking(booking);

      if (success) {
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text('Booking successful! 🎉')),
        );
        // Optionally navigate to a confirmation screen or user's bookings
        Navigator.pop(context); // Go back to previous screen after success
      } else {
        setState(() {
          _errorMessage = 'Failed to create booking. Please try again.';
        });
      }
    } catch (e) {
      setState(() {
        _errorMessage = 'Error: ${e.toString()}';
      });
    } finally {
      setState(() {
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    final TextTheme textTheme = Theme.of(context).textTheme;
    final ColorScheme colorScheme = Theme.of(context).colorScheme;

    final totalPrice = _numberOfTickets * widget.selectedTrain.price;

    return Scaffold(
      appBar: AppBar(
        title: const Text('Book Your Ticket'),
        backgroundColor: colorScheme.primary,
        foregroundColor: colorScheme.onPrimary,
      ),
      body: _isLoading
          ? const Center(child: CircularProgressIndicator())
          : SingleChildScrollView(
              padding: const EdgeInsets.all(16.0),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  // Train Details Card
                  Card(
                    elevation: 4,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                    margin: const EdgeInsets.only(bottom: 24),
                    child: Padding(
                      padding: const EdgeInsets.all(16.0),
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          Text(
                            'Selected Train Details',
                            style: textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold),
                          ),
                          const Divider(height: 20),
                          _buildDetailRow(
                            context,
                            Icons.train,
                            'Train Name:',
                            widget.selectedTrain.trainName,
                          ),
                          _buildDetailRow(
                            context,
                            Icons.location_on,
                            'Route:',
                            '${widget.selectedTrain.departure} → ${widget.selectedTrain.arrival}',
                          ),
                          _buildDetailRow(
                            context,
                            Icons.departure_board,
                            'Departure Time:',
                            widget.selectedTrain.departureTime,
                          ),
                          _buildDetailRow(
                            context,
                            Icons.access_time,
                            'Arrival Time:',
                            widget.selectedTrain.arrivalTime,
                          ),
                          _buildDetailRow(
                            context,
                            Icons.money,
                            'Price per ticket:',
                            '\$${widget.selectedTrain.price.toStringAsFixed(2)}',
                            isBoldValue: true,
                          ),
                          _buildDetailRow(
                            context,
                            Icons.event_seat,
                            'Available Seats:',
                            '${widget.selectedTrain.availableSeats}',
                          ),
                        ],
                      ),
                    ),
                  ),

                  // Number of Tickets Section
                  Text(
                    'Number of Tickets',
                    style: textTheme.titleMedium?.copyWith(fontWeight: FontWeight.bold),
                  ),
                  const SizedBox(height: 12),
                  Row(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      IconButton(
                        icon: const Icon(Icons.remove_circle),
                        iconSize: 40,
                        color: colorScheme.primary,
                        onPressed: _decrementTickets,
                      ),
                      const SizedBox(width: 16),
                      Text(
                        '$_numberOfTickets',
                        style: textTheme.displaySmall?.copyWith(fontWeight: FontWeight.bold),
                      ),
                      const SizedBox(width: 16),
                      IconButton(
                        icon: const Icon(Icons.add_circle),
                        iconSize: 40,
                        color: colorScheme.primary,
                        onPressed: _incrementTickets,
                      ),
                    ],
                  ),
                  if (_errorMessage != null)
                    Padding(
                      padding: const EdgeInsets.only(top: 8.0),
                      child: Center(
                        child: Text(
                          _errorMessage!,
                          style: textTheme.bodyMedium?.copyWith(color: colorScheme.error),
                          textAlign: TextAlign.center,
                        ),
                      ),
                    ),
                  const SizedBox(height: 24),

                  // Total Price
                  Align(
                    alignment: Alignment.centerRight,
                    child: Text(
                      'Total Price: \$${totalPrice.toStringAsFixed(2)}',
                      style: textTheme.headlineSmall?.copyWith(
                        fontWeight: FontWeight.bold,
                        color: colorScheme.secondary,
                      ),
                    ),
                  ),
                  const SizedBox(height: 24),

                  // Book Now Button
                  SizedBox(
                    width: double.infinity,
                    child: ElevatedButton.icon(
                      onPressed: _bookTrain,
                      icon: const Icon(Icons.check_circle),
                      label: Text(
                        'Book Now',
                        style: textTheme.titleMedium?.copyWith(color: colorScheme.onPrimary),
                      ),
                      style: ElevatedButton.styleFrom(
                        backgroundColor: colorScheme.primary,
                        padding: const EdgeInsets.symmetric(vertical: 16),
                        shape: RoundedRectangleBorder(
                          borderRadius: BorderRadius.circular(10),
                        ),
                      ),
                    ),
                  ),
                ],
              ),
            ),
    );
  }

  // Helper widget to build consistent detail rows
  Widget _buildDetailRow(BuildContext context, IconData icon, String label, String value, {bool isBoldValue = false}) {
    final TextTheme textTheme = Theme.of(context).textTheme;
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 4.0),
      child: Row(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Icon(icon, size: 20, color: Theme.of(context).colorScheme.onSurfaceVariant),
          const SizedBox(width: 8),
          Expanded(
            child: Text.rich(
              TextSpan(
                children: [
                  TextSpan(
                    text: '$label ',
                    style: textTheme.bodyLarge?.copyWith(fontWeight: FontWeight.w600),
                  ),
                  TextSpan(
                    text: value,
                    style: textTheme.bodyLarge?.copyWith(
                      fontWeight: isBoldValue ? FontWeight.bold : FontWeight.normal,
                      color: isBoldValue ? Theme.of(context).colorScheme.primary : null,
                    ),
                  ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }
}