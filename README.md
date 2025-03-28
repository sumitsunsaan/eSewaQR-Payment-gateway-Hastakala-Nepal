# eSewa Payment Gateway for PrestaShop

**Disclaimer:** This module is not developed or endorsed by eSewa Official. Developed independently for PrestaShop merchants in Nepal.

**Developer:** [Sumit Dahal](https://github.com/sumitsunsaan)  
**Company:** [Hastakala Nepal Private Limited](https://hastakalanepal.com)  
**Contact:** 📞 +977-9845053769 | 📧 [admin@hastakalanepal.com](mailto:admin@hastakalanepal.com)

![Module Interface](https://via.placeholder.com/800x400.png?text=eSewa+Payment+Gateway+Preview)

## ⚠️ Important Notice
This module is provided **as-is** without any warranties. While developed for Hastakala Nepal Private Limited, it's open for community use. Use at your own risk. We are not responsible for any payment processing issues or financial losses.

## Features

### Merchant Features
- **QR Code Payments**
  - Upload custom QR codes (PNG/JPEG, max 2MB)
  - Thumbnail preview in admin panel
  - Mobile-optimized download option
- **Mobile Payments**
  - Fixed +977 country code
  - 10-digit number validation (9XXXXXXXXX)
  - One-click copy functionality
- **Security**
  - MIME type verification
  - File size restrictions
  - XSS protection

### Customer Features
- Dual payment options at checkout
- Auto-formatted phone number display
- QR code download for mobile users
- Multi-language support

## Requirements
- PrestaShop 8.2.x
- PHP 7.4+
- OpenSSL extension
- GD Library

## Installation

```bash
# Manual Installation
cd modules/
git clone https://github.com/sumitsunsaan/esewa-prestashop.git esewaqr
chmod -R 755 esewaqr/
```

**PrestaShop Admin:**
1. Go to `Modules → Module Manager`
2. Click "Upload a module"
3. Select `esewaqr.zip`
4. Configure after installation

## Configuration
1. **Phone Number:**
   - Navigate to `Modules → eSewa Payment → Configure`
   - Enter 10-digit number starting with 9 (e.g., 9845053769)

2. **QR Code:**
   - Upload via admin panel
   - Requirements:
     - Format: PNG/JPEG
     - Size: ≤2MB
     - Recommended: 300x300px

![Admin Configuration](https://via.placeholder.com/600x300.png?text=Admin+Configuration+Preview)

## Support

**Commercial Support:**  
Hastakala Nepal Clients Only  
📧 [admin@hastakalanepal.com](mailto:admin@hastakalanepal.com)  
📞 +977-9845053769 (9AM-5PM NPT)

**Community Support:**  
🐛 [GitHub Issues](https://github.com/sumitsunsaan/esewa-prestashop/issues)  
💬 [PrestaShop Forums](https://www.prestashop.com/forums)

## License
MIT License - See [LICENSE](LICENSE)

**Permissions:**
- Use commercially
- Modify privately
- Distribute with attribution

**Limitations:**
- No liability
- No warranty
- Must include original license

## Contributing
1. Fork repository
2. Create feature branch (`git checkout -b feature/improvement`)
3. Commit changes (`git commit -m 'Add some feature'`)
4. Push to branch (`git push origin feature/improvement`)
5. Open Pull Request

## Disclaimer (Reiterated)
This module is **NOT** designed by eSewa or any affiliated companies. Verify all transactions manually.

```diff
- Warning: Use at Your Own Risk
+ We are not responsible for any financial discrepancies
+ Always verify payments manually
+ Test thoroughly before production use
```

---

**Need Custom Payment Solutions?**  
Contact our development team:  
🌐 [https://hastakalanepal.com](https://hastakalanepal.com)  
📧 [admin@hastakalanepal.com](mailto:admin@hastakalanepal.com)
