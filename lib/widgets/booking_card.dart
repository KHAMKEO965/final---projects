// lib/widgets/booking_card.dart
import 'package:flutter/material.dart';
import '../models/booking_model.dart'; // Make sure this path is correct

class BookingCard extends StatelessWidget {
  final Booking booking;
  final VoidCallback onCancel; // Callback for the cancel button

  const BookingCard({super.key, required this.booking, required this.onCancel});

  @override
  Widget build(BuildContext context) {
    final TextTheme textTheme = Theme.of(context).textTheme;
    final ColorScheme colorScheme = Theme.of(context).colorScheme;

    return Card(
      margin: const EdgeInsets.symmetric(vertical: 8, horizontal: 8),
      elevation: 4,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Flexible( // Use Flexible to prevent text overflow
                  child: Text(
                    booking.trainName,
                    style: textTheme.titleLarge?.copyWith(fontWeight: FontWeight.bold, color: colorScheme.primary),
                    overflow: TextOverflow.ellipsis, // Add ellipsis for long names
                  ),
                ),
                const SizedBox(width: 8), // Spacing between train name and date
                Text(
                  'Booked on: ${booking.bookingDate.day}/${booking.bookingDate.month}/${booking.bookingDate.year}',
                  style: textTheme.bodySmall?.copyWith(color: colorScheme.onSurfaceVariant),
                ),
              ],
            ),
            const Divider(height: 20),
            _buildDetailRow(context, Icons.route, 'Route:', '${booking.departureStation} → ${booking.arrivalStation}'),
            _buildDetailRow(context, Icons.access_time, 'Departure:', booking.departureTime),
            _buildDetailRow(context, Icons.access_time_filled, 'Arrival:', booking.arrivalTime),
            _buildDetailRow(context, Icons.confirmation_number, 'Tickets:', '${booking.numberOfTickets}'),
            _buildDetailRow(context, Icons.payments, 'Total Price:', '\$${booking.totalPrice.toStringAsFixed(2)}', isBoldValue: true),
            const SizedBox(height: 16),
            Align(
              alignment: Alignment.bottomRight,
              child: OutlinedButton.icon(
                onPressed: onCancel,
                icon: const Icon(Icons.cancel, size: 20),
                label: const Text('Cancel Booking'),
                style: OutlinedButton.styleFrom(
                  foregroundColor: colorScheme.error,
                  side: BorderSide(color: colorScheme.error),
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(8)),
                ),
              ),
            ),
          ],
        ),
      ),
    );
  }

  // Helper widget to build consistent detail rows for BookingCard
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