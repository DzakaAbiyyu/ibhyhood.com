import { useState } from "react";
import { useCart } from "../context/CartContext"; // 1. Import Cart Context
import { formatRupiah } from "../api"

export default function MerchDetailModal({ merch, onClose }) {
    const [quantity, setQuantity] = useState(1);
    const { addToCart } = useCart(); // 2. Destructure fungsi addToCart

    if (!merch) return null;

    const handleAddToCart = () => {
        addToCart(merch, quantity); // 3. Kirim data produk dan kuantitas
        onClose(); // 4. Tutup modal setelah ditambah ke keranjang
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-3 sm:p-4">
            <div className="absolute inset-0" onClick={onClose} />

            <div className="relative z-10 w-full max-w-2xl rounded-2xl bg-white p-4 sm:p-6 shadow-2xl overflow-y-auto max-h-[92vh]">
                <button
                    onClick={onClose}
                    className="absolute right-4 top-4 text-gray-400 hover:text-black text-lg font-bold bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full flex items-center justify-center"
                >
                    ✕
                </button>

                <div className="grid grid-cols-1 md:grid-cols-2 gap-6 mt-2">
                    <div className="h-60 md:h-72 bg-[#C1D0FF]/20 rounded-xl overflow-hidden relative flex items-center justify-center">
                        {merch.badge && (
                            <span className="absolute top-3 left-3 bg-[#6B7CB4] text-white text-xs font-bold px-2 py-0.5 rounded">
                                {merch.badge}
                            </span>
                        )}
                        <img
                            src={merch.image}
                            alt={merch.title}
                            className="w-full h-full object-cover hidden"
                            onLoad={(e) => e.currentTarget.classList.remove('hidden')}
                        />
                        {merch.image ? (
                            <img src={merch.image} alt={merch.title} className="w-full h-full object-cover" />
                        ) : (
                            <span className="text-gray-400 text-[10px] md:text-xs">[ Product Preview ]</span>
                        )}
                    </div>

                    <div className="flex flex-col justify-between space-y-4">
                        <div>
                            <span className="text-[11px] font-semibold text-[#6B7CB4] uppercase tracking-wider">
                                {merch.type} • {merch.theme}
                            </span>
                            <h2 className="text-xl sm:text-2xl font-bold text-gray-900 mt-1">
                                {merch.title}
                            </h2>
                            <p className="text-lg font-extrabold text-[#6B7CB4] mt-2">
                                {formatRupiah(merch.price)}
                            </p>
                            <p className="text-gray-500 text-xs sm:text-sm mt-3 leading-relaxed">
                                {merch.description || "Official merchandise item crafted with high-quality materials. Perfect for collectors and fans."}
                            </p>
                        </div>

                        <div className="space-y-3 border-t pt-4">
                            <div className="flex items-center gap-3">
                                <span className="text-xs font-medium text-gray-600">Quantity:</span>
                                <div className="flex items-center border rounded-lg">
                                    <button
                                        onClick={() => setQuantity((prev) => Math.max(1, prev - 1))}
                                        className="px-3 py-1 text-gray-600 hover:bg-gray-100 font-bold text-xs"
                                    >
                                        -
                                    </button>
                                    <span className="px-3 py-1 text-xs font-bold">{quantity}</span>
                                    <button
                                        onClick={() => setQuantity((prev) => prev + 1)}
                                        className="px-3 py-1 text-gray-600 hover:bg-gray-100 font-bold text-xs"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>

                            {/* Tombol yang sudah dihubungkan */}
                            <button
                                onClick={handleAddToCart}
                                className="w-full bg-[#6B7CB4] hover:bg-[#5a6a9d] text-white font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm transition-colors flex items-center justify-center gap-2"
                            >
                                + Add to Cart
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}