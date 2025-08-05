import 'package:flutter/material.dart';

// แบบจำลองการจอง
class Booking {
  String trainName;
  String travelDate;

  Booking({required this.trainName, required this.travelDate});
}

class CrudScreen extends StatefulWidget {
  const CrudScreen({super.key});

  @override
  State<CrudScreen> createState() => _CrudScreenState();
}

class _CrudScreenState extends State<CrudScreen> {
  final TextEditingController _trainNameController = TextEditingController();
  final TextEditingController _travelDateController = TextEditingController();

  List<Booking> bookings = [];

  void _addBooking() {
    final trainName = _trainNameController.text.trim();
    final travelDate = _travelDateController.text.trim();

    if (trainName.isNotEmpty && travelDate.isNotEmpty) {
      setState(() {
        bookings.add(Booking(trainName: trainName, travelDate: travelDate));
        _trainNameController.clear();
        _travelDateController.clear();
      });
    }
  }

  void _editBooking(int index) {
    final booking = bookings[index];
    _trainNameController.text = booking.trainName;
    _travelDateController.text = booking.travelDate;

    showDialog(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text('แก้ไขการจอง'),
        content: Column(
          mainAxisSize: MainAxisSize.min,
          children: [
            TextField(
              controller: _trainNameController,
              decoration: const InputDecoration(labelText: 'ชื่อขบวนรถไฟ'),
            ),
            TextField(
              controller: _travelDateController,
              decoration: const InputDecoration(labelText: 'วันที่เดินทาง'),
            ),
          ],
        ),
        actions: [
          TextButton(
            onPressed: () => Navigator.pop(context),
            child: const Text('ยกเลิก'),
          ),
          TextButton(
            onPressed: () {
              setState(() {
                bookings[index] = Booking(
                  trainName: _trainNameController.text.trim(),
                  travelDate: _travelDateController.text.trim(),
                );
                _trainNameController.clear();
                _travelDateController.clear();
              });
              Navigator.pop(context);
            },
            child: const Text('บันทึก'),
          ),
        ],
      ),
    );
  }

  void _deleteBooking(int index) {
    setState(() {
      bookings.removeAt(index);
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('การจองของฉัน'),
      ),
      body: Padding(
        padding: const EdgeInsets.all(16),
        child: Column(
          children: [
            TextField(
              controller: _trainNameController,
              decoration: const InputDecoration(labelText: 'ชื่อขบวนรถไฟ'),
            ),
            TextField(
              controller: _travelDateController,
              decoration: const InputDecoration(labelText: 'วันที่เดินทาง (เช่น 2025-08-10)'),
            ),
            const SizedBox(height: 10),
            ElevatedButton.icon(
              onPressed: _addBooking,
              icon: const Icon(Icons.add),
              label: const Text('เพิ่มการจอง'),
            ),
            const SizedBox(height: 20),
            Expanded(
              child: bookings.isEmpty
                  ? const Center(child: Text('ยังไม่มีการจอง'))
                  : ListView.builder(
                      itemCount: bookings.length,
                      itemBuilder: (context, index) {
                        final booking = bookings[index];
                        return Card(
                          child: ListTile(
                            title: Text('🚆 ${booking.trainName}'),
                            subtitle: Text('📅 วันที่เดินทาง: ${booking.travelDate}'),
                            trailing: Row(
                              mainAxisSize: MainAxisSize.min,
                              children: [
                                IconButton(
                                  icon: const Icon(Icons.edit, color: Colors.blue),
                                  onPressed: () => _editBooking(index),
                                ),
                                IconButton(
                                  icon: const Icon(Icons.delete, color: Colors.red),
                                  onPressed: () => _deleteBooking(index),
                                ),
                              ],
                            ),
                          ),
                        );
                      },
                    ),
            ),
          ],
        ),
      ),
    );
  }
}
