import { useState } from "react";

export default function GigDetailModal({ gig, onClose }) {
    const [activeTab, setActiveTab] = useState("basic");

    if (!gig) return null;

    // Nomor WhatsApp tujuan (Ganti angka ini dengan nomor WhatsApp milikmu, gunakan format 628...)
    const PHONE_NUMBER = "6281234567890";

    const currentPackage = gig.packages?.[activeTab] || {
        price: gig.startingPrice || "Rp 100k",
        desc: "Custom service request."
    };

    const handleOrderWhatsApp = () => {
        const packageName = activeTab === "standart" ? "Standard" : activeTab.charAt(0).toUpperCase() + activeTab.slice(1);

        // Format pesan otomatis ke WhatsApp
        const message = `Halo, saya berminat untuk memesan jasa *${gig.title}*.\n\n` +
            `*Paket:* ${packageName}\n` +
            `*Harga:* ${currentPackage.price}\n` +
            `*Detail Paket:* ${currentPackage.desc}\n\n` +
            `Apakah slot masih tersedia?`;

        const encodedMessage = encodeURIComponent(message);
        const waUrl = `https://wa.me/${PHONE_NUMBER}?text=${encodedMessage}`;

        // Buka link WhatsApp di tab baru
        window.open(waUrl, "_blank");
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-3 sm:p-4">
            <div className="absolute inset-0" onClick={onClose} />

            <div className="relative z-10 w-full max-w-4xl rounded-2xl bg-white p-4 sm:p-6 shadow-2xl overflow-y-auto max-h-[92vh]">
                <button
                    onClick={onClose}
                    className="absolute right-4 top-4 text-gray-400 hover:text-black text-lg font-bold bg-gray-100 hover:bg-gray-200 w-8 h-8 rounded-full flex items-center justify-center"
                >
                    ✕
                </button>

                <h2 className="text-lg sm:text-2xl font-bold mb-4 pr-8">{gig.title}</h2>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                    {/* Main Image & Mini Gallery */}
                    <div className="md:col-span-2 space-y-3">
                        <div className="h-48 sm:h-64 w-full bg-gray-200 rounded-xl overflow-hidden">
                            {gig.coverImage && (
                                <img src={gig.coverImage} alt={gig.title} className="w-full h-full object-cover" />
                            )}
                        </div>
                        <div className="grid grid-cols-4 gap-2">
                            {(gig.gallery && gig.gallery.length > 0 ? gig.gallery : [1, 2, 3, 4]).map((img, idx) => (
                                <div key={idx} className="h-12 sm:h-16 bg-gray-200 rounded-lg overflow-hidden">
                                    {typeof img === 'string' && (
                                        <img src={img} alt="" className="w-full h-full object-cover" />
                                    )}
                                </div>
                            ))}
                        </div>
                    </div>

                    {/* Pricing Package */}
                    <div className="border border-gray-200 rounded-xl overflow-hidden flex flex-col justify-between h-fit bg-white">
                        <div className="grid grid-cols-3 bg-indigo-50 text-center font-medium text-xs sm:text-sm border-b border-gray-200">
                            {["basic", "standart", "premium"].map((tab) => (
                                <button
                                    key={tab}
                                    onClick={() => setActiveTab(tab)}
                                    className={`py-2.5 capitalize transition-all ${activeTab === tab
                                        ? "bg-[#6B7CB4] text-white font-bold"
                                        : "text-gray-600 hover:bg-indigo-100"
                                        }`}
                                >
                                    {tab === "standart" ? "Standard" : tab}
                                </button>
                            ))}
                        </div>

                        <div className="p-4 space-y-4">
                            <div>
                                <p className="text-base sm:text-lg font-bold text-gray-900">{currentPackage.price}</p>
                                <p className="text-xs sm:text-sm text-gray-600 mt-1">{currentPackage.desc}</p>
                            </div>

                            <button
                                onClick={handleOrderWhatsApp}
                                className="w-full bg-[#25D366] hover:bg-[#20ba5a] text-white font-semibold py-2.5 px-4 rounded-xl text-xs sm:text-sm flex items-center justify-center gap-2 transition-colors shadow-sm"
                            >
                                Order via WhatsApp <span>→</span>
                            </button>
                        </div>
                    </div>
                </div>

                {/* Description */}
                <div className="mt-6 border-t pt-4">
                    <h3 className="font-bold text-sm sm:text-base mb-1">Description this gig</h3>
                    <p className="text-gray-600 text-xs sm:text-sm leading-relaxed whitespace-pre-line">
                        {gig.description || "No description provided for this service."}
                    </p>
                </div>
            </div>
        </div>
    );
}